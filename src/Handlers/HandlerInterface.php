<?php namespace MergeTech\ContactForm\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use MergeTech\ContactForm\ContactForm;

/**
 * Interface HandlerInterface
 * @package MergeTech\ContactForm\Handlers
 */
interface HandlerInterface
{
    /**
     * @param Mailer $mail - the emailer
     * @param array $submitted_data - the user submitted data
     * @param ContactForm $contact_form - details about the contact form - where to send emails to, what fields appear
     * @param bool $clear_errors - if true, then any $this->errors (or whatever property in a implementation of this) should be cleared every time this method is run
     * @return mixed
     */
    public function handleContactSubmission(Mailer $mail, array $submitted_data, ContactForm $contact_form, $clear_errors = true);

    /**
     * return array of any errors that may have occurred, or return [] if no errors
     * @return mixed
     */
    public function getErrors();
}