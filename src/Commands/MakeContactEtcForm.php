<?php

namespace MergeTech\ContactForm\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeContactFormForm extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:contactform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new empty contact form for mergetech/contact';

    /**
     * The type of class being generated.
     *
     * @var string
     * @return null|mixed
     */
    protected $type = 'ContactFormForm';

    public function handle()
    {
        if (!is_array(config("contact"))){
            $this->error("ERROR: The config file contact.php does not exist, or is not returning an array. Have you done the vendor:publish command? Please see the docs on https://mergetech.com/contact");
            return;
        }

        // create the file:
        $return= parent::handle();
        // output info messages:
        $this->outputMessages($return);
        return $return;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/ContactForm.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\ContactFormForms';
    }

    /**
     * @param $return
     */
    protected function outputMessages($return)
    {
        if (is_null($return)) {
            // success!
            // let's output some help/info

            // get the file that was created.
            $name = $this->qualifyClass($this->getNameInput());
            $path = $this->getPath($name);
            $filename = basename($path);

            $this->info("You can find the file in:");
            $this->line("/app/ContactFormForms/$filename");

            $this->warn("Please edit that file as required.");

            $this->line("--------------------------");
            $this->warn(wordwrap(" ** Please update the 'contact_forms' array in your config/contact.php and include the full path of the new file. **"));
            $this->info(("It should be the following: "));

            $this->line("app_path('ContactFormForms/$filename')");

            $this->line("--------------------------");

            $this->info(wordwrap("(please see https://mergetech.com/contact for the docs that explain how the configs should look)"));

            $this->line("--------------------------");


            $this->info(wordwrap("If you have more than one contact form, please see the docs on my site (you will also have to add some custom routes."));

            $this->line("--------------------------");
            $this->warn("All done! Please scroll up and read the previous messages!");
            $this->line("Visit https://mergetech.com/contact for docs/more help!");
            $this->line("BTW - Need to hire a php dev (EU/London based)? contact me https://mergetech.com/ :)");

        }
    }
}
