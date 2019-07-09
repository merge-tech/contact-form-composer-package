<?php namespace MergeTech\ContactForm\FieldTypes;

class Textarea extends Text
{
    /**
     * What blade view file should this field use on the contact form?
     *
     * @return string
     */
    public function getView()
    {
        return "contact::fields.Textarea";
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


}
