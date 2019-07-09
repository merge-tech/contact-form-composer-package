<?php namespace MergeTech\ContactForm\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\Mail\ContactFormMail;

/**
 * Class HandleContactSubmission
 * @package MergeTech\ContactForm\Handlers
 */
class HandleContactSubmission implements HandlerInterface
{
    /**
     * Currently unused, but may be used in the future.
     *
     * should hold an array of string error messages.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Process what needs to happen with $submitted_data
     *
     * @todo: check for errors from Mailer object when we try to ->send() it, and report those back to the user
     * @param Mailer $mail - the mailer obj
     * @param array $submitted_data - basically \Request::all()
     * @param ContactForm $contact_form - details about the form (form fields, where to send the email to, etc)
     * @param bool $clear_errors - if true, it will clear any current errors.
     * @return bool - false = error, true = success
     */
    public function handleContactSubmission(Mailer $mail, array $submitted_data, ContactForm $contact_form, $clear_errors = true)
    {

        $this->clearErrors($clear_errors);

        // we don't actually use the errors array in this implementation.
        // but maybe it will be useful for other uses in the future.
        // any  validation errors should have been caught in the request rules
        // in the future we should check for any errors from the Mail object
        // (todo)

        if (count($this->errors)) {
            return false;
        }

        // send the email...
        $mail->to($contact_form->send_to)
            ->send(new ContactFormMail($submitted_data, $contact_form));

        return true;
    }

    /**
     * return an array of any errors that occurred.
     * Currently not actually used, but might be used in future.
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $clear_errors
     */
    protected function clearErrors($clear_errors)
    {
        if ($clear_errors) {
            $this->errors = [];
        }
    }
}