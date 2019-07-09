<?php

/*
 * This is a default set of config options for a contact form.
 * Only to be used for TESTING
 */

namespace App\ContactFormForms;

use MergeTech\ContactForm\ContactFormConfigurator;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\FieldTypes\Checkbox;
use MergeTech\ContactForm\FieldTypes\Email;
use MergeTech\ContactForm\FieldTypes\RecaptchaV2Invisible;
use MergeTech\ContactForm\FieldTypes\Text;
use MergeTech\ContactForm\FieldTypes\Textarea;


###################################################################
###                                                             ###
###     Adding a new contact form.  You can have more than      ###
###     one contact form. If you add more than one you must     ###
###     add a custom route to your web.php file                 ###
###                                                             ###
###################################################################



return ContactForm::newContactForm("main_contact_form")
    ->sendTo("UPDATE_THIS_TO_SOMETHING@example.com")// what email address shall we send the contact form response to? (i.e. your email address)
    ->humanReadableFormName("Main Contact Form")// what is the name of this form?

    // optional, but you might want to pass some variables on the form page. You can replace this with an empty array -
    ->setFormViewVars([
        'title' => "Our contact page",
        'meta_desc' => "Get in touch with us!",
//                    'sidebar_section_title'=>"Welcome to our contact page",
    ], [
        'title' => "Our contact page",
        'meta_desc' => "Get in touch with us!",
    ])
    ->setHtmlAboveAndBelowForm(
        '<div style="text-align:center;"><h3>Contact us</h3><p>Please use the contact form below to get in touch! </p></div>', '')
    ->setSubmitButtonTextAndClasses("Send!", "btn btn-primary")
    ->addFields(

        [
            Text::newNamed("your_name")// field name
            ->setLabelName("Your name")
                ->markAsRequiredField()
//                        ->setAsFromName()
                ->setAsReplyToName(),

            Email::newNamed("email")
                ->setLabelName("Your email address")
                ->setPlaceholderValue("you@example.com")
                ->markAsRequiredField()
                ->max(200)// max length
                ->min(4)// min length
//                        ->setAsFromAddress()
                ->setAsReplyToAddress(),


            Text::newNamed("your_location")// field name
            ->setLabelName("Location")
                ->markAsOptional()
                ->max(100), // max length

            Textarea::newNamed("message")
                ->setLabelName("Your message")
//                        ->defaultValue("I wanted to get in touch to ask about...")
                ->setDescription("Please give as much detail as possible")
                ->markAsRequiredField()
                ->max(5000),

            // other, unused built in field types:
            // checkbox - useful if you want the user to agree to some terms and conditions.
//                    Checkbox::newNamed("agree_to_terms","Agree to our terms?")->markAsRequiredField(),

            // you must have CAPTCHA_SITEKEY and CAPTCHA_SECRET set up in your .env (or in config/captcha.php)
//                    RecaptchaV2Invisible::spam("g-recaptcha-response")->setLabelName("Spam protection"),

        ]


    );





