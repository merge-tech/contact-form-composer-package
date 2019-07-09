<?php

namespace MergeTech\ContactForm\FieldTypes;

/**
 * Class BaseFieldType
 * @package MergeTech\ContactForm\FieldTypes
 */
abstract class BaseFieldType implements BaseFieldInterface
{

    /**
     * Should this be ignored for things like validation or emailing?
     * This should be true for things such as captcha fields.
     *
     * @var bool
     */
    public $ignore = false;

    /**
     * Min length (for validation).
     *
     * If this is not false, then it will get added to the validation rules
     *
     * @var bool|int
     */
    protected $min = false;
    /**
     * Max length (for validation).
     *
     * If this is not false, then it will get added to the validation rules
     *
     * @var bool|int
     */
    protected $max = false;

    /**
     * The field name.
     *
     * i.e. <input name='$field_name'>
     *
     * @var string
     */
    public $field_name;

    /**
     * What should be in the <label>...</label> tag in the HTML?
     *
     * I.e. a human readable name for this field
     *
     * @var null|string
     */
    public $label;

    /**
     * A short (1 sentence?) description about the field
     *
     * @var null|string
     */
    public $description;
    /**
     * What should the default value be?
     *
     * Most times you will leave this blank
     *
     * @var null|string
     */
    public $default;

    /**
     * is this field required?
     *
     * true = required field
     * false = optional field
     *
     * @var  boolean
     */
    public $required;

    /**
     * What should the place holder be?
     * <input placeholder='$placeholder'>
     *
     * @var null|string
     */
    public $placeholder;

    /**
     * Any custom attributes for the field
     *
     * These will get sent to the view, so you can add a custom view file to do something with these
     * @var array|null
     */
    public $custom_attributes;

    /**
     * BaseFieldType constructor.
     *
     * You can either use this (which sets up most of the properties), or use the FieldClass::newNamed("your_field")->labeled("Your Field")->required()-> etc way of doing it.
     *
     * @param $field_name
     * @param $title
     * @param $description
     * @param $default
     * @param bool $required
     * @param $placeholder
     * @param array $custom_attributes
     */
    public function __construct($field_name, $title = null, $description = null, $default = null, $required = false, $placeholder = null, array $custom_attributes = [])
    {
        $this->setFieldName($field_name);

        $this->label = $title ? $title : str_slug($this->field_name);

        $this->default = $default;
        $this->description = $description;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->custom_attributes = $custom_attributes;
    }

    public function valueTagAttribute()
    {
        $val = e(old($this->field_name, $this->default));
        return ' value="' . $val . '" ';
    }

    public function placeholderTagAttribute()
    {

        if ($this->placeholder) {
            $placeholder = e($this->placeholder);
            return ' placeholder="' . $placeholder . '" ';
        }

        return '';
    }

    public function requiredTagAttribute()
    {
        // if $this->required, then return 'required' so it can be placed in the <input>
        return $this->required ? " required " : "";
    }

    /**
     * Create a new instance, and set the field name to $name
     *
     *
     * @param $name
     * @return static
     */
    public static function newNamed($name)
    {
        $field = new static($name);
        return $field;
    }


    /**
     *  Set the field name.
     *
     * @param $field_name
     * @return $this
     */
    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
        return $this;
    }


    /**
     * Set the label property.
     *
     * This should be a human readable title of the field.
     *
     * i.e. <label>$label</label>
     * @param $label
     * @return $this
     */
    public function setLabelName($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Set the default value
     *
     * i.e. <input value='$value'>
     * @param $default
     * @return $this
     */
    public function setDefaultValue($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Set the description for this field.
     *
     * This is normally a 1 or 2 sentence description.
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the placeholder value
     * i.e. <input placeholder=$placeholder>
     * @param $placeholder
     * @return $this
     */
    public function setPlaceholderValue($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Set any custom attributes that you want.
     *
     * This might be useful if you have custom view files and want to send some data or variables there.
     * @param $custom_attributes
     * @return $this
     */
    public function addCustomAttributes($custom_attributes)
    {
        $this->custom_attributes = $custom_attributes;
        return $this;
    }

    /**
     * Mark this field as required
     * @return $this
     */
    public function markAsRequiredField()
    {
        $this->required = true;
        return $this;
    }

    /**
     * Mark this field as optional
     *
     * @return $this
     */
    public function markAsOptional()
    {
        $this->required = false;
        return $this;
    }

    /**
     * Set the min value for this field. Used in validation.
     * See the standard laravel validation min rules
     *
     * @param $min
     * @return $this
     */
    public function min(int $min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * Set the max value for this field. Used in validation.
     * See the standard laravel validation max rules
     *
     * @param $max
     * @return $this
     */
    public function max(int $max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * returns the max value
     * @return bool|int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * returns the min value
     * @return bool|int
     */
    public function getMin()
    {
        return $this->min;
    }


    /**
     * generate the rule for either required or optional (nullable)
     *
     * @return string
     */
    protected function isRequiredOrNullable()
    {
        return $this->required ? 'required' : 'nullable';
    }

    /**
     * given $rules (defined in a subclass), also add any additional rules from the properties.
     *
     * @param $rules
     * @return array
     */
    public function parse_rules(array $rules)
    {


        if ($this->getMin()) {
            $rules[] = "min:" . $this->getMin();
        }

        if ($this->getMax()) {
            $rules[] = "max:" . $this->getMax();
        }

        $rules[] = $this->isRequiredOrNullable();

        return $rules;

    }


    /**
     * Used if any processing is needed (such as converting from submitted <select> keys to their values)
     * @param $submitted_data
     * @return string
     */
    public function forEmailOutput($submitted_data)
    {
        return nl2br(e(trim($submitted_data)));
    }


}