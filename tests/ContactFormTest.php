<?php

use MergeTech\ContactForm\ContactFormConfigurator;
use MergeTech\ContactForm\ContactFormServiceProvider;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\FieldGenerator\GetContactFormFieldData;
use MergeTech\ContactForm\FieldTypes\Email;
use MergeTech\ContactForm\FieldTypes\Textarea;
use MergeTech\ContactForm\FieldTypes\Text;

class ContactFormTest extends \Tests\TestCase
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


    public function test_can_create_a_contact_form()
    {

        $fields = [
            // first field:
            \MergeTech\ContactForm\FieldTypes\Text::newNamed("your_name")// field name (<input name=$field_name>)
            ->setLabelName("Your alt name")// the <label>$label_name</label> value
            ->markAsRequiredField()// required in the Request validation + <input required >
//                        ->setAsFromName() // if you want the email to set the 'from name' to this value
            ->setAsReplyToName(), // if you want to email 'reply to name' to this value

            // second field:
            Email::newNamed("email")// the field name <input name='email'>
            ->setLabelName("Your email address")// the <label>Your email address</label>
            ->setPlaceholderValue("you@example.com")// the default placeholder value (<input placeholder=$placeholder>)
            ->markAsRequiredField()// makes it required, in the Request validation and also in the HTML with <input required>
            ->max(200)// max length (via request validation rule)
            ->min(4)// min length (via request validation rule)
//                        ->setAsFromAddress() // send the email to you with this as the 'from' address
            ->setAsReplyToAddress(), // mark the email's "reply to" as this value


            // another field -
            Text::newNamed("your_location")// field name
            ->setLabelName("Location")
                ->markAsOptional()// opposite of ->markAsRequiredField(). This isn't really needed, as all fields are optional unless ->markAsRequired() was set
                ->max(100), // max length

            // another field:
            Textarea::newNamed("message")
                ->setLabelName("Your message")
//                        ->defaultValue("I wanted to get in touch to ask about...")
                ->setDescription("Please give as much detail as possible")// an optional bit of text that is displayed below the field
                ->markAsRequiredField()
                ->max(5000),

            // Here are some other field types you can use.
            // Just uncomment them and move it where you want

            // checkbox - useful if you want the user to agree to some terms and conditions.
//                    \MergeTech\ContactForm\FieldTypes\Checkbox::newNamed("agree_to_terms") //<input type='checkbox'>
//                            ->markAsRequiredField()->setLabelName("Agree to terms?"),


            // Do you want to use the invisible recaptcha from Google?
            // If so, please make sure that you have CAPTCHA_SITEKEY and CAPTCHA_SECRET set up in your .env (or in config/captcha.php)
            // and uncomment the next line
            \MergeTech\ContactForm\FieldTypes\RecaptchaV2Invisible::spam()// do not change the field name!
            ->setLabelName("Spam protection"), // probably not needed, as it should be invisible!

        ];

        $name = "this_is_unique";
        $sendto = "UPDATE_THIS_TO_SOMETHING@example.com";
        $readableFormName = "Main Contact Form";
        $above = "above123";
        $below = "below987";
        $sendbutton = "send";
        $sendcss = "css_class";

        $contact_form = ContactForm::newContactForm($name)
            ->sendTo($sendto)
            ->humanReadableFormName($readableFormName)
            ->addFields($fields)
            ->setFormViewVars([
                'title' => "Our contact page",
                'meta_desc' => "Get in touch with us!",
//                    'sidebar_section_title'=>"Welcome to our contact page",
            ], [
                'title' => "Our contact page",
                'meta_desc' => "Get in touch with us!",
            ])
            ->setHtmlAboveAndBelowForm($above, $below)
            ->setSubmitButtonTextAndClasses($sendbutton, $sendcss);


        $this->assertTrue($contact_form->contact_form_name == $name);
        $this->assertTrue($contact_form->send_to == $sendto);
        $this->assertTrue($contact_form->human_readable_form_name == $readableFormName);
        $this->assertTrue($contact_form->html_above_form == $above);
        $this->assertTrue($contact_form->html_below_form == $below);
        $this->assertTrue($contact_form->submit_button_text == $sendbutton);
        $this->assertTrue($contact_form->submit_button_css_classes == $sendcss);


    }


    public function test_contact_form_validates_with_error_for_incorrect_form()
    {
        $name = "this_is_unique";

        $contact_form = ContactForm::newContactForm($name);

        $this->expectException(\Exception::class);
        $contact_form->validate();
    }

    public function test_contact_form_thows_error_if_form_name_not_valid_with_spaces()
    {
        $name = "with spaces";
        $this->expectException(\Exception::class);
        ContactForm::newContactForm($name);
    }

    public function test_contact_form_thows_error_if_form_name_not_valid_with_dashes()
    {
        $name = "with-dashes";
        $this->expectException(\Exception::class);
        ContactForm::newContactForm($name);
    }

    public function test_contact_form_validates_ok_for_correct_form()
    {

        $fields = [
            // first field:
            \MergeTech\ContactForm\FieldTypes\Text::newNamed("your_name")// field name (<input name=$field_name>)
            ->setLabelName("Your alt name")// the <label>$label_name</label> value
            ->markAsRequiredField()// required in the Request validation + <input required >
//                        ->setAsFromName() // if you want the email to set the 'from name' to this value
            ->setAsReplyToName(), // if you want to email 'reply to name' to this value


        ];

        $name = "this_is_unique";
        $sendto = "UPDATE_THIS_TO_SOMETHING@example.com";
        $readableFormName = "Main Contact Form";
        $above = "above123";
        $below = "below987";
        $sendbutton = "send";
        $sendcss = "css_class";

        $contact_form = ContactForm::newContactForm($name)
            ->sendTo($sendto)
            ->humanReadableFormName($readableFormName)
            ->addFields($fields)
            ->setFormViewVars([
                'title' => "Our contact page",
                'meta_desc' => "Get in touch with us!",
            ], [
                'title' => "Our contact page",
                'meta_desc' => "Get in touch with us!",
            ])
            ->setHtmlAboveAndBelowForm($above, $below)
            ->setSubmitButtonTextAndClasses($sendbutton, $sendcss);


        $this->assertTrue(is_object($contact_form->validate()));


    }


}
