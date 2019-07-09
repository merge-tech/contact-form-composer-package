<?php

namespace MergeTech\ContactForm\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use MergeTech\ContactForm\ContactForm;

class ContactFormSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array */
    public $submitted_data;

    /** @var ContactForm */
    public $contact_form;

    public function __construct(array $submitted_data, ContactForm $contact_form)
    {
        $this->submitted_data = $submitted_data;
        $this->contact_form = $contact_form;
    }

}
