<?php namespace MergeTech\ContactForm\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Mail\Mailer;
use MergeTech\ContactForm\ContactFormServiceProvider;
use MergeTech\ContactForm\ContactForm;
use MergeTech\ContactForm\Events\ContactFormError;
use MergeTech\ContactForm\Events\ContactFormSent;
use MergeTech\ContactForm\Events\ContactFormSubmitted;
use MergeTech\ContactForm\FieldGenerator\FieldGeneratorInterface;
use MergeTech\ContactForm\Handlers\HandlerInterface;
use MergeTech\ContactForm\Requests\ContactFormSubmittedRequest;

/**
 * Class ContactFormController
 * @package MergeTech\ContactForm\Controllers
 */
class ContactFormController extends Controller
{
    /** @var  ContactForm - set via $this->getContactForm() */
    protected $contactForm;

    /**
     * Show the requested contact form.
     *
     * @param FieldGeneratorInterface $fieldGenerator
     * @param string $contact_form_name
     * @return mixed
     */
    public function form(FieldGeneratorInterface $fieldGenerator, $contact_form_name = ContactFormServiceProvider::DEFAULT_CONTACT_FORM_KEY)
    {
        $this->getContactForm($fieldGenerator, $contact_form_name);

        // 'please fill out the form:'
        return view("contact::form", $this->contactForm->view_params['form_view_vars'])
            ->withFormUrl(route('contact.send.' . $contact_form_name))
            ->withContactFormDetails($this->contactForm)
            ->withFields($this->contactForm->fields());
    }

    /**
     * Send the message, and show the confirmation view.
     *
     * @param ContactFormSubmittedRequest $request
     * @param Mailer $mail
     * @param FieldGeneratorInterface $fieldGenerator
     * @param HandlerInterface $handler
     * @param $contact_form_name
     *
     * @return \Illuminate\View\View
     */
    public function send(ContactFormSubmittedRequest $request, Mailer $mail, FieldGeneratorInterface $fieldGenerator, HandlerInterface $handler, string $contact_form_name)
    {

        $this->getContactForm($fieldGenerator, $contact_form_name);

        event(new ContactFormSubmitted($request->all(), $this->contactForm));

        if (!$handler->handleContactSubmission($mail, $request->all(), $this->contactForm)) {
            return $this->error($request, $handler);
        }

        event(new ContactFormSent($request->all(), $this->contactForm));

        // 'thanks, we will get in touch soon!'
        return view("contact::sent", $this->contactForm->view_params['sent_view_vars']);

    }

    /**
     * Send the ContactFormError event, and return a redirectResponse with the old input and any errors from the handler.
     *
     * @param ContactFormSubmittedRequest $request
     * @param HandlerInterface $handler
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function error(ContactFormSubmittedRequest $request, HandlerInterface $handler)
    {
        event(new ContactFormError($request->all(), $this->contactForm, $handler->getErrors()));

        return back()->withInput()->withErrors($handler->getErrors());
    }

    /**
     * Get the requested ContactForm and set it as a property  on $this.
     * @param FieldGeneratorInterface $fieldGenerator
     * @param string $contact_form_name
     */
    protected function getContactForm(FieldGeneratorInterface $fieldGenerator, string $contact_form_name)
    {
        $this->contactForm = $fieldGenerator->contactFormNamed($contact_form_name);
    }

}