<?php

namespace App\Console\Commands;

use App\Console\Traits\InteractiveCommandTrait;
use App\Services\ContactService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class ContactsCreate extends Command
{
    use InteractiveCommandTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:create
                            {--firstName= : First name of the contact}
                            {--surname= : Surname of the contact}
                            {--email= : Email address of the contact}
                            {--phone= : Phone number of the contact (format: +61xxxxxxxxx or +64xxxxxxxx)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new contact via the command line';

    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * Constructor
     */
    public function __construct(ContactService $contactService)
    {
        parent::__construct();
        $this->contactService = $contactService;

        $this->setFieldDefinitions([
            [
                'name' => 'firstName',
                'option' => 'firstName',
                'prompt' => 'First Name',
                'description' => 'First name of the contact',
                'required' => true
            ],
            [
                'name' => 'surname',
                'option' => 'surname',
                'prompt' => 'Surname',
                'description' => 'Surname of the contact',
                'required' => true
            ],
            [
                'name' => 'email',
                'option' => 'email',
                'prompt' => 'Email',
                'description' => 'Email address of the contact',
                'required' => true
            ],
            [
                'name' => 'phone',
                'option' => 'phone',
                'prompt' => 'Phone (format: +61xxxxxxxxx or +64xxxxxxxx)',
                'description' => 'Phone number of the contact (format: +61xxxxxxxxx or +64xxxxxxxx)',
                'required' => true
            ]
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Create a new contact');

        $data = $this->collectFieldValues();

        try {
            $result = $this->contactService->createContact($data);

            return $this->handleResult($result, "Contact created successfully");
        } catch (ValidationException $e) {
            return $this->handleValidationError($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
