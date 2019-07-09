<?php

use MergeTech\ContactForm\ContactFormConfigurator;
use MergeTech\ContactForm\ContactFormServiceProvider;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\FieldGenerator\GetContactFormFieldData;
use MergeTech\ContactForm\FieldTypes\Email;
use MergeTech\ContactForm\FieldTypes\Textarea;
use MergeTech\ContactForm\FieldTypes\Text;

class RulesTest extends \Tests\TestCase
{


    /** Setup the contact form config */
    public function setUp()
    {
        parent::setUp();
        $this->app->singleton(ContactFormConfigurator::class, function () {
            // send a custom array of what config files we want to (by default) include
            // this stops errors being thrown that are not relevant to any testing
            return ContactFormConfigurator::createNew([
                __DIR__ . "/TestConfigs/main_contact_form_config.php"
            ]);
        });
    }

    /** Make some basic tests that the ContactFormSubmittedRequest request returns some rules */
    public function test_basic_rules()
    {
        $request = new \MergeTech\ContactForm\Requests\ContactFormSubmittedRequest();
        $request->contactFormId = ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY;
        $resp = $request->rules(app()->make(ContactFormConfigurator::class));

        $this->assertTrue(is_array($resp));

        // while we are here, quickly test this:
        $this->assertTrue($request->authorize());

    }

}
