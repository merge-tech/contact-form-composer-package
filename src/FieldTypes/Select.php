<?php namespace MergeTech\ContactForm\FieldTypes;
use Illuminate\Validation\Rule;

/**
 * Class Select (dropdown)
 * @package MergeTech\ContactForm\FieldTypes
 */
class Select extends BaseFieldType
{
    protected $options=[];

    /**
     * If true, this is a source for the 'reply to' name
     * @var bool
     */
    protected $is_reply_to_name=false;
    /**
     * If true, this is a source for the 'from' name
     * @var bool
     */
    protected $is_from_name=false;

    /**
     * What blade view file should this field use on the contact form?
     *
     * @return string
     */
    public function getView()
    {
        return "contact::fields.Select";
    }


    /**
     * Return an array of rules for the validation.
     *
     * @return array
     */
    public function rules()
    {
        return parent::parse_rules([
            Rule::in(array_keys($this->options))
        ]);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {

        $this->options = $options;
        return $this;
    }

    /**
     * Used if any processing is needed (such as converting from submitted <select> keys to their values)
     *
     * @param $submitted_data
     * @return string
     */
    public function forEmailOutput($submitted_data)
    {
        if (array_key_exists($submitted_data, $this->options)) {
            $submitted_data = $this->options[$submitted_data];
        }

        return parent::forEmailOutput($submitted_data);
    }

}