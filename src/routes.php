<?php

// The default (enabled by default) routes for the contact form.
// You can change the options in /config/contact.php

// you can disable this by setting the config('contact.include_default_routes') to false.
// then you can manually add your own routes to your web.php file.


use MergeTech\ContactForm\ContactFormServiceProvider;

Route::group([
    'middleware' => ['web'],
    'prefix' => config('contact.contact_us_slug', 'contact-us')],

    function () {

        // default form. You must have an item in /app/ContactFormForms/ that has its form name set to ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY for this to work!
        $contact_field_group_name = ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY;

        // the contact form:
        Route::get("/", '\MergeTech\ContactForm\Controllers\ContactFormController@form')
            ->name('contact.form.' . $contact_field_group_name)//contact.form.main_contact_form
            ->defaults('contactFormId', $contact_field_group_name);

        // processing the submitted data:
        Route::post("/", '\MergeTech\ContactForm\Controllers\ContactFormController@send')
            ->name('contact.send.' . $contact_field_group_name)// contact.send.main_contact_form
            ->defaults('contactFormId', $contact_field_group_name);

        // want to add more than one contact form? Don't edit this page! See the docs on https://merge.africa/blogs/custom-contact-form-integration/!
        // you can add as many as needed.
});






// want to add more than one? Please go to https://merge.africa/blogs/custom-contact-form-integration/ to read the docs on how to do this.

