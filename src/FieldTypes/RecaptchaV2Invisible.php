<?php namespace MergeTech\ContactForm\FieldTypes;

/**
 * Class RecaptchaV2Invisible
 * @package MergeTech\ContactForm\FieldTypes
 */
class RecaptchaV2Invisible extends BaseFieldType
{

    /**
     * @var bool
     */
    public $ignore = true;

    /**
     * What blade view file should this field use on the contact form?
     *
     * @return string
     */
    public function getView()
    {
        return "contact::fields.RecaptchaV2Invisible";
    }

    /**
     * set the field name - this must be hard coded to work!
     * @param $field_name
     * @return $this
     */
    public function setFieldName($field_name)
    {
        // this MUST be g-recaptcha-response
        $this->field_name = 'g-recaptcha-response';
        return $this;
    }

    /**
     * a custom static method for creating this  with the hard coded field_name
     *
     * @return static
     */
    public static function spam()
    {
        return self::newNamed("g-recaptcha-response");
    }

    /**
     * Return an array of rules for the validation.
     *
     * @return array
     */
    public function rules()
    {

        // no need to pass it to parent::parse_rules()
        return [
            'required',
            'captcha'
        ];
    }

}
