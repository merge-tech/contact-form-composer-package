<?php namespace MergeTech\ContactForm;

use MergeTech\ContactForm\FieldTypes\BaseFieldType;

/**
 * This holds all of the details needed for a contact form. Including: who to email it to, what fields should be shown on the contact page.
 *
 * @package MergeTech\ContactForm
 */

class ContactForm
{

    /**
     * A name for the contact form.
     * Should be alphanum (with underscores)
     *
     * @var
     */
    public $contact_form_name;
    /**
     * A nice, human readable name
     * Used in places such as in the default email message ("A contact form response from: $human_readable_form_name")
     * @var string
     */
    public $human_readable_form_name = 'Contact Form';
    /**
     * What email should this contact form send to? (i.e. your email address)
     * @var
     */
    public $send_to;
    /**
     * What variables should we pass to the views?
     *
     * 'form_view_vars' will be sent to the contact form view
     * 'sent_view_vars' will be sent to the 'thanks for getting in touch' view.
     *
     * Optional - you can leave these as two empty arrays.
     * @var array
     */
    public $view_params = [
        'form_view_vars' => [],
        'sent_view_vars' => []
    ];
    /**
     * What HTML to use above the contact form?
     * Anything you put here is not escaped.
     * You can leave it empty
     * @var string
     */
    public $html_above_form = '';
    /**
     * What HTML to use below the contact form.
     * @see $html_above_form
     * @var string
     */
    public $html_below_form = '';
    /**
     * What should the send button value be?
     * ie <input type='submit' value='$this->submit_button_text' >
     * @var string
     */
    public $submit_button_text = 'Send!';
    /**
     * What css class should the submit button use?
     *
     * by default it uses some Bootstrap classes
     * @var string
     */
    public $submit_button_css_classes = 'btn btn-primary btn-lg';

    /**
     * an array of BasicFieldType objects (such as Text, Textarea, etc) - each one will be an item in the contact form.
     *
     * If you have a form with a name field, email field, and message field then this array should have 3 items in it.
     * Use $this->addFields()
     * @var array
     */
    protected $fields = [];


    /**
     * Do not allow direct (new ContactForm).
     * (private)
     * ContactForm constructor.
     */
    private function __construct()
    {
    }

    /**
     * Instantiate a new object of this class, and return it.
     *
     * $contact_form_name must be alpha numeric/underscore.
     *
     * @param string $contact_form_name
     * @return ContactForm
     * @throws \Exception
     */
    public static function newContactForm(string $contact_form_name)
    {
        if (str_slug($contact_form_name, "_") !== $contact_form_name) {
            throw new \Exception("Invalid \$contact_form_name ($contact_form_name) - it should be alpha numeric, with underscores (A-Z0-9_)");
        }

        $form = new self;
        $form->contact_form_name = $contact_form_name;
        return $form;
    }


    /**
     * This is a simple check that the bare minimum data is provided for this contact form to work.
     * The form submission validation is NOT done here! Check the /src/Requests/ContactFormSubmittedRequest.php file for that
     *
     * It will throw an exception if there is an error.
     * If all is ok, it returns $this.
     *
     * @todo: refactor this, it doesn't really belong in a ContactForm class.
     * Also, it could use the same validation methods as the main laravel validation.
     *
     * @return $this
     * @throws \Exception
     */
    public function validate()
    {
        // check it has everything set
        // this does not validate any data, just that the ContactForm has the required fields.

        $required = [
            'contact_form_name' => ['required', 'string'],
            'send_to' => ['required', 'email'],
        ];

        foreach ($required as $fieldName => $validateAgainst) {
            $this->checkValidationForField($validateAgainst, $fieldName);
        }

        // all is good, return this!
        return $this;

    }


    protected function checkValidationRule($rule)
    {

    }


    /**
     * Add an array of fields.
     * Each item in the array should be a subclass of BaseFieldType (such as Text, Checkbox, etc).
     * This is enforced by the ->addField(BaseFieldType $type) call.
     *
     * @param array $fields
     * @return $this
     */
    public function addFields(array $fields)
    {
        array_map([$this,'addField'], $fields);
        return $this;
    }

    /**
     * Add a field.
     *
     * @param BaseFieldType $field
     * @return $this
     */
    public function addField(BaseFieldType $field)
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Returns an array of the fields for this contact form
     * (the array should hold BasicFieldType objects (such as Text, Textarea, etc) - each one will be an item in the contact form.
     * @return array
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * What HTML to use above the contact form?
     * Anything you put here is not escaped.
     * You can leave it empty
     *
     * ie <input type='submit' value='$this->submit_button_text' class='$this->submit_button_css_classes' >
     *
     * @param $submit_button_text
     * @param $submit_button_css_classes
     * @return $this
     */
    public function setSubmitButtonTextAndClasses($submit_button_text, $submit_button_css_classes)
    {
        $this->submit_button_text = $submit_button_text;
        $this->submit_button_css_classes = $submit_button_css_classes;
        return $this;
    }

    /**
     * What HTML to use above/below the contact form?
     * Anything you put here is not escaped.
     * You can leave it empty
     *
     * @param string|null $above
     * @param string|null $below
     * @return $this
     */
    public function setHtmlAboveAndBelowForm(string $above = null, string $below = null)
    {

        // the html to appear above the contact form
        $this->html_above_form = $above;

        // and the html to appear below it.
        $this->html_below_form = $below;

        return $this;
    }

    /**
     *  What variables should we pass to the views?
     *
     * 'form_view_vars' will be sent to the contact form view
     * 'sent_view_vars' will be sent to the 'thanks for getting in touch' view.
     *
     * Optional - you can leave these as two empty arrays.
     *
     * @param array $form_view_vars
     * @param array $sent_view_vars
     * @return $this
     */
    public function setFormViewVars(array $form_view_vars = [], array $sent_view_vars = [])
    {
        // optional, but you might want to pass some variables on the contact form. You can replace this with an empty array -
        $this->view_params = [
            'form_view_vars' => $form_view_vars,
            'sent_view_vars' => $sent_view_vars];
        return $this;

    }

    /**
     * A nice, human readable name (e.g "Our Contact Page")
     * @param string $human_readable_form_name
     * @return $this
     */
    public function humanReadableFormName(string $human_readable_form_name)
    {
        $this->human_readable_form_name = $human_readable_form_name;
        return $this;
    }

    /**
     * What email should this contact form send to? (i.e. your email address)
     *
     * @param string $email
     * @return $this
     */
    public function sendTo(string $email)
    {
        // what email address shall we send the contact form response to? (i.e. your email address)
        $this->send_to = $email;
        return $this;
    }

    /**
     * @param $validateAgainst
     * @param $fieldName
     * @throws \Exception
     */
    protected function checkValidationForField($validateAgainst, $fieldName)
    {
        foreach ($validateAgainst as $rule) {
            switch ($rule) {
                case "required":
                    if (!trim($this->$fieldName)) {
                        throw new \Exception("\$this->$fieldName must be set, but is not set");
                    }
                    break;
                case 'string':
                    if (!is_string($this->$fieldName)) {
                        throw new \Exception("\$this->$fieldName must be a string, but it is not");
                    }
                    break;
                case 'email':
                    if (!filter_var($this->$fieldName, FILTER_VALIDATE_EMAIL)) {
                        throw new \Exception("\$this->$fieldName must be an email address, but it is not");
                    }
                    break;
            };
        }
    }


}