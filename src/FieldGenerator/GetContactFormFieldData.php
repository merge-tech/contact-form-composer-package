<?php

namespace MergeTech\ContactForm\FieldGenerator;

use MergeTech\ContactForm\ContactFormConfigurator;
use MergeTech\ContactForm\ContactForm;

/**
 * Class FieldGenerator
 * @todo this is a bit of a helper class really. Maybe refactor.
 *
 * @package MergeTech\ContactForm\FieldGenerator
 */
class GetContactFormFieldData implements FieldGeneratorInterface
{
    /**
     * Returns the requested ContactForm object
     *
     * @param $contact_form_name
     * @return ContactForm
     * @throws \Exception
     */
    public function contactFormNamed($contact_form_name)
    {
        /** @var ContactFormConfigurator $configurator */
        $configurator = app()->make(ContactFormConfigurator::class);

        /** @var ContactForm */
        return $configurator->getContactForm($contact_form_name);
    }
}