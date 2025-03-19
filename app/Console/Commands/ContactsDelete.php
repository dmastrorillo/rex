<?php

namespace App\Console\Commands;

use App\Console\Traits\InteractiveCommandTrait;
use App\Services\ContactService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class ContactsDelete extends Command
{
    use InteractiveCommandTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:delete
                            {--id= : ID of the contact to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a contact via the command line';

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
                'description' => 'ID of the contact to delete',
                'required' => true
            ]
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Delete a contact');

        $data = $this->collectFieldValues();

        try {

            $contact = $this->contactService->getContact($data);
            $result = $this->contactService->deleteContact($contact);
            return $this->handleResult($result, "Contact deleted successfully");
        } catch (ValidationException $e) {
            return $this->handleValidationError($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
