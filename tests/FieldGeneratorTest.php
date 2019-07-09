<?php

use MergeTech\ContactForm\ContactFormConfigurator;
use MergeTech\ContactForm\ContactFormServiceProvider;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\FieldGenerator\GetContactFormFieldData;
use MergeTech\ContactForm\FieldTypes\Email;
use MergeTech\ContactForm\FieldTypes\Textarea;

class FieldGeneratorTest extends \Tests\TestCase
{


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


    public function test_field_generator_returns_array()
    {


        Config::set('contact.contact_form_pages.' . ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY, [

            'send_to' => "mergetech@sent.com",
            'fields' =>
                function () {
                    return [
                        new Email('testemail'),
                    ];
                }]);

        $field_generator = new GetContactFormFieldData();
        $returned = $field_generator->contactFormNamed(ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY);

        $fields = $returned->fields();
        $this->assertTrue(is_array($fields));
    }


    public function test_field_generator_returns_array_if_given_closure()
    {
        $returned =
            ['fields' =>
                function () {
                    return [new Textarea('something')];
                }
            ];

        $this->assertTrue(is_array($returned['fields']()));
    }


    public function test_field_generator_throws_error_if_invalid_contact_form_id()
    {
        $this->expectException(\Exception::class);
        $field_generator = new GetContactFormFieldData();
        $field_generator->contactFormNamed('something_very_invalid_12345678');
    }


    public function test_can_load_multiple_config_options()
    {
        // lets load the default one... (main_contact_form)


        Config::set('contact.contact_form_pages.' . ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY, [

            'send_to' => "mergetech@sent.com",
            'fields' =>
                function () {
                    return [
                        new Email('testemail'),
                        new Email('testemail'),
                        new Email('testemail'),
                        new Email('testemail'),
                    ];
                }]);

        $field_generator = new GetContactFormFieldData();

        $main_contact_form = $field_generator->contactFormNamed(ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY);
        $fields = $main_contact_form->fields();
        $this->assertTrue(is_array($fields));
        $this->assertTrue(count($fields) > 1);


        /** @var ContactFormConfigurator $config */
        $config = app()->make(ContactFormConfigurator::class);
        $config->addContactForm(ContactForm::newContactForm('mytest')
            ->sendTo("test@example.com")
            ->addFields([
                    new Email('testemail'),
                ]
            )
        );


        $mytest_details = $field_generator->contactFormNamed('mytest');

        $mytest_fields = $mytest_details->fields();

        $this->assertTrue(is_array($mytest_fields));
        $this->assertTrue(count($mytest_fields) === 1);
        $this->assertTrue($mytest_fields[0]->field_name === 'testemail');

    }

}

