<?php

namespace MergeTech\ContactForm\FieldTypes;

use Illuminate\Validation\Rule;

/**
 * Class Checkbox
 * @package MergeTech\ContactForm\FieldTypes
 */
class Checkbox extends BaseFieldType
{

    /**
     * return the blade view that this field type should use
     *
     * @return string
     */
    public function getView()
    {
        return "contact::fields.Checkbox";
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            Rule::in('1'),
        ];
    }
}