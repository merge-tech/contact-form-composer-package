<?php

use MergeTech\ContactForm\ContactFormConfigurator;
use MergeTech\ContactForm\ContactFormServiceProvider;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\FieldGenerator\GetContactFormFieldData;
use MergeTech\ContactForm\FieldTypes\Email;
use MergeTech\ContactForm\FieldTypes\Textarea;

class MailTest extends \Tests\TestCase
{


    /** Setup the config for contact forms with test data */
    public function setUp()
    {
        parent::setUp();
        $this->app->singleton(ContactFormConfigurator::class, function () {
            // send a custom array of what config files we want to (by default) include
            // this stops errors being thrown that are not relevant to any testing
            return ContactFormConfigurator::createNew([
                __DIR__ . "/TestConfigs/main_contact_form_config.php",
                __DIR__ . "/TestConfigs/alt.php"
            ]);
        });
    }

    public function test_contact_etc_mail()
    {


        $field_generator = new GetContactFormFieldData();
        $form = $field_generator->contactFormNamed('alt');
        $submitted = [
            'your_name' => "myname",
            "email" => "someemail@email.com",
            "message" => "Hello world message here",
        ];
        $mail = new \MergeTech\ContactForm\Mail\ContactFormMail($submitted, $form);
        $this->assertArrayHasKey('your_name', $mail->submitted_data);
        $build_resp = $mail->build();

        // let's just double check the right type got returned.
        $this->assertTrue(get_class($build_resp) == \MergeTech\ContactForm\Mail\ContactFormMail::class);


        // check that the mail has its ReplyTo name/email set:
        $this->assertTrue(count($mail->replyTo) == 1 && $mail->replyTo[0]['name'] == $submitted['your_name'] && $mail->replyTo[0]['address'] == $submitted['email']);

        // check the mail has its From name/email set:
        $this->assertTrue(count($mail->from) == 1 && $mail->from[0]['name'] == $submitted['your_name'] && $mail->from[0]['address'] == $submitted['email']);


    }

}
