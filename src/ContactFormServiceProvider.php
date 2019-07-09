<?php

namespace MergeTech\ContactForm;

use Illuminate\Support\ServiceProvider;
use MergeTech\ContactForm\Commands\MakeContactFormForm;
use MergeTech\ContactForm\FieldGenerator\GetContactFormFieldData;
use MergeTech\ContactForm\FieldGenerator\FieldGeneratorInterface;
use MergeTech\ContactForm\Handlers\HandleContactSubmission;

/**
 * Class ContactFormMainServiceProvider
 * @package MergeTech\ContactForm
 */
class ContactFormServiceProvider extends ServiceProvider
{
    /**
     * The default contact form key.
     * This is used so we can set up the routes for a contact form.
     */
    const DEFAULT_CONTACT_FORM_KEY = 'main_contact_form';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutes();
        $this->publishesFiles();
        $this->loadViewsFrom(__DIR__ . "/Views/contact", 'contact');
        $this->commands([
            MakeContactFormForm::class
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->makeBindings();
    }


    /**
     * Load the routes (if enabled in config)
     * If you want to have more than the default contact form you must edit your own web.php routes file. See the docs for details.
     */
    protected function loadRoutes()
    {
        if (config("blogetc.include_default_routes", true)) {
            // load default routes
            include(__DIR__ . "/routes.php");
        }
    }

    /**
     * Views + ContactForm config (contact.php)
     *
     * tagged with 'contact'
     *
     * Use it with:
     * php artisan make:contactform MainContactForm
     */
    protected function publishesFiles()
    {
        $tag = 'contact';
        $this->publishes([
            __DIR__ . '/Views/contact' => base_path('resources/views/vendor/contact'),
            __DIR__ . '/Config/contact.php' => config_path('contact.php'),
        ], $tag);
    }

    /**
     * make bindings
     */
    protected function makeBindings()
    {
        $this->app->bind(FieldGeneratorInterface::class, function () {
            // this is a bit of a helper really. Not ideal. todo: refactor
            return new GetContactFormFieldData();
        });
        $this->app->bind(Handlers\HandlerInterface::class, function () {
            // the class that takes the input, and does what it needs to (email it!)
            return new HandleContactSubmission();
        });

        $this->app->singleton(ContactFormConfigurator::class, function () {
            // the configurator - it holds a collection of all ContactForm's (which have all the details about who to send to, what fields to use, etc)
            return ContactFormConfigurator::createNew();
        });
    }
}


