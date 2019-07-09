<?php namespace MergeTech\ContactForm\FieldTypes;

/**
 * Class Email
 * @package MergeTech\ContactForm\FieldTypes
 */
class Email extends BaseFieldType
{

    /**
     * @var bool
     */
    protected $is_default_from_address = false;
    /**
     * @var bool
     */
    protected $is_reply_to_address = false;

    /**
     * What blade view file should this field use on the contact form?
     *
     * @return string
     */
    public function getView()
    {
        return "contact::fields.Email";
    }


    /**
     * Return an array of rules for the validation.
     *
     * @return array
     */
    public function rules()
    {
        return parent::parse_rules([
            'email',
        ]);
    }

    /**
     * Set this field as the source for the reply to email address
     * @return $this
     */
    public function setAsReplyToAddress()
    {
        $this->is_reply_to_address = true;
        return $this;
    }

    /**
     * checks if this field is a source for the reply to email address
     * @return bool
     */
    public function isReplyToAddress()
    {
        return $this->is_reply_to_address;
    }

    /**
     * set this field as the source of the 'from' email address
     * @return $this
     */
    public function setAsFromAddress()
    {
        $this->is_default_from_address = true;
        return $this;
    }

    /**
     * check if this field is a source for the 'from' email address
     * @return bool
     */
    public function isFromAddress()
    {
        return $this->is_default_from_address;
    }


}