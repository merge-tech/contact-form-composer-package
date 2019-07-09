<?php namespace MergeTech\ContactForm\FieldTypes;

/**
 * Class Text
 * @package MergeTech\ContactForm\FieldTypes
 */
class Text extends BaseFieldType
{

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
        return "contact::fields.Text";
    }


    /**
     * Return an array of rules for the validation.
     *
     * @return array
     */
    public function rules()
    {

        return parent::parse_rules([
            'string',
        ]);
    }

    /**
     * Mark this field as a source for the 'reply to' name
     * @return $this
     */
    public function setAsReplyToName()
    {

        $this->is_reply_to_name = true;
        return $this;
    }

    /**
     * check if this field is a source for the 'reply to' name
     * @return bool
     */
    public function isReplyToName()
    {
        return $this->is_reply_to_name;
    }

    /**
     * set this field as a source for the 'from' name
     * @return $this
     */
    public function setAsFromName()
    {

        $this->is_from_name = true;
        return $this;
    }

    /**
     * checks if this field is a source for the 'from' name
     * @return bool
     */
    public function isFromName()
    {
        return $this->is_from_name;
    }

}