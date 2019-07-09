<?php
namespace MergeTech\ContactForm\Tests;

use MergeTech\ContactForm\ContactFormServiceProvider;
use MergeTech\ContactForm\FieldTypes\BaseFieldType;
use MergeTech\ContactForm\FieldTypes\Checkbox;
use MergeTech\ContactForm\FieldTypes\Email;
use MergeTech\ContactForm\FieldTypes\RecaptchaV2Invisible;
use MergeTech\ContactForm\FieldTypes\Text;
use MergeTech\ContactForm\FieldTypes\Textarea;


/** Some misc tests that don't belong elsewhere */
class MiniTest extends \Tests\TestCase
{

    /** this is a simple test to check that the const DEFAULT_FORM_PAGE_ID is still set to main_contact_form.
     *  there is no reason why it should be set to anything else.
     */
    public function test_default_form_page_id_is_still_main_contact_form()
    {
        $this->assertTrue(ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY == 'main_contact_form');
    }

    public function test_field_types_seem_ok()
    {
        // fieldTypeObject test to ensure that none of the field types have any obvious errors
        foreach(
            [
                Checkbox::class,
                Email::class,
                RecaptchaV2Invisible::class,
                Textarea::class,
                Text::class
            ] as $fieldType) {

            $fieldTypeObject = new $fieldType("something","Something");
            $view = $fieldTypeObject->getView();

            $this->assertTrue(is_string($view), $fieldType . " view was not string");

            // render fieldTypeObject view, this test will fail if the view produces an error
            $rendered = view($view, ['field'=>$fieldTypeObject,'errors'=>optional(null)])->render();
            $this->assertTrue(is_string($rendered) && strlen($rendered));
        }
    }
}
