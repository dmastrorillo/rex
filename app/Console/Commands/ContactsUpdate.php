<?php

namespace App\Console\Commands;

use App\Console\Traits\InteractiveCommandTrait;
use App\Services\ContactService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class ContactsUpdate extends Command
{
    use InteractiveCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:update
                        {--id= : ID of the contact to update}
                        {--firstName= : First name of the contact}
                        {--surname= : Surname of the contact}
                        {--email= : Email address of the contact}
                        {--phone= : Phone number of the contact (format: +61xxxxxxxxx or +64xxxxxxxx)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a contact via the command line';

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
                'name' => 'id',
                'option' => 'id',
                'prompt' => 'Contact ID',
                'description' => 'ID of the contact to update',
                'required' => true
            ],
            [
                'name' => 'firstName',
                'option' => 'firstName',
                'prompt' => 'First Name',
                'description' => 'First name of the contact',
                'required' => false
            ],
            [
                'name' => 'surname',
                'option' => 'surname',
                'prompt' => 'Surname',
                'description' => 'Surname of the contact',
                'required' => false
            ],
            [
                'name' => 'email',
                'option' => 'email',
                'prompt' => 'Email',
                'description' => 'Email address of the contact',
                'required' => false
            ],
            [
                'name' => 'phone',
                'option' => 'phone',
                'prompt' => 'Phone number',
                'description' => 'Phone number of the contact (format: +61xxxxxxxxx or +64xxxxxxxx)',
                'required' => false
            ]
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Update a contact');

        $data = $this->collectFieldValues();
        $id = $data['id'];
        unset($data['id']);


        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        if (empty($data)) {
            $this->error('No update data provided. Please specify at least one field to update.');
            return 1;
        }

        try {
            $result = $this->contactService->updateContact($data, $id);
            return $this->handleResult($result, "Contact updated successfully");
        } catch (ValidationException $e) {
            return $this->handleValidationError($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
