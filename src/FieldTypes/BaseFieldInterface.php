<?php namespace MergeTech\ContactForm\FieldTypes;

/**
 * Interface BaseFieldInterface
 * @package MergeTech\ContactForm\FieldTypes
 */
interface BaseFieldInterface
{
    /**
     * return the blade view that this field type should use
     *
     * @return string
     */
    public function getView();

    /**
     * return an array of the basic rules that apply for this field, for validation.
     *
     * Some rules will be auto generated, based on some property values (such as min/max)
     * @return mixed
     */
    public function rules();
}