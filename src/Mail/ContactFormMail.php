<?php

namespace MergeTech\ContactForm\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\FieldTypes\BaseFieldType;
use MergeTech\ContactForm\FieldTypes\Email;

/**
 * Class ContactFormMail
 * @package MergeTech\ContactForm\Mail
 */
class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * array of the submitted POST data
     * @var array
     */
    public $submitted_data=[];
    /**
     * All details (such as who to send to, the contact form fields, etc) about the contact form
     *
     * @var ContactForm
     */
    public $contact_form;

    /**
     * ContactFormMail constructor.
     * @param array $submitted_data
     * @param ContactForm $contact_form
     */
    public function __construct(array $submitted_data, ContactForm $contact_form)
    {
        $this->submitted_data = $submitted_data;
        $this->contact_form = $contact_form;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /** @var Mailable $message */
        $message = $this->markdown('contact::contact_form_template',
            ['submitted_data' => $this->submitted_data,
                'fields' => $this->contact_form->fields(),
                'contact_form' => $this->contact_form]
        );

        $message = $this->setupMessageHeaders($message);
        return $message;
    }

    /**
     * Set some message headers such as reply to, etc.
     *
     * @todo: refactor this, it is a very messy.
     * @param $message
     * @return mixed
     */
    protected function setupMessageHeaders(Mailable $message)
    {
        // build the from_email_address email and reply to email details...
        // this is a bit bulky, but it is quite simple...
        // todo: refactor a little, the main loop is a too large.

        $from_email_address = $from_name = $reply_to_email_address = $reply_to_name = null;

        /** @var BaseFieldType|Email $field */
        foreach ($this->contact_form->fields() as $field) {

            if ($field->ignore) {
                // used for spam/recaptcha
                continue;
            }
            if (empty($this->submitted_data[$field->field_name])) {
                // no submitted data, so nothing to do here...
                continue;
            }

            if (is_a($field, Email::class)) {
                if ($field->isFromAddress()) {
                    $from_email_address = $this->submitted_data[$field->field_name];
                }
                if ($field->isReplyToAddress()) {
                    $reply_to_email_address = $this->submitted_data[$field->field_name];
                }
            }

            if (method_exists($field, "isReplyToName")) {
                if ($field->isReplyToName()) {
                    $reply_to_name = $this->submitted_data[$field->field_name];
                }
            }
            if (method_exists($field, "isFromName")) {
                if ($field->isFromName()) {
                    $from_name = $this->submitted_data[$field->field_name];
                }
            }
        }


        // ok, now we have gone through everything, do we have a $from_email_address (and maybe a $from_name)?
        if ($from_email_address) {
            // set the from email address, and maybe $from_name if we have that info
            $message = $message->from($from_email_address, $from_name);
        }

        // and do we have a $reply_to_email_address (and maybe a $reply_to_name)?
        if ($reply_to_email_address) {
            // set the default 'reply to' in the email that will get sent to you once this form is submitted
            $message = $message->replyTo($reply_to_email_address, $reply_to_name);
        }

        return $message;
    }
}
