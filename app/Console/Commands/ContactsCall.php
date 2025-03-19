<?php

namespace App\Console\Commands;

use App\Console\Traits\InteractiveCommandTrait;
use App\Services\CallService;
use App\Services\ContactService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class ContactsCall extends Command
{
    use InteractiveCommandTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:call
                            {--id= : The ID of the contact you want to call}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls a contact via the command line';

    /**
     * @var CallService
     */
    protected $callService;

    /**
     * Constructor
     */
    public function __construct(CallService $callService)
    {
        parent::__construct();
        $this->callService = $callService;

        $this->setFieldDefinitions([
            [
                'name' => 'id',
                'option' => 'id',
                'prompt' => 'Contact ID',
                'description' => 'The ID of the contact you want to call',
                'required' => true
            ],
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Calling contact...');

        $data = $this->collectFieldValues();

        try {
            $result = $this->callService->initiateCall($data['id']);

            return $this->handleResult($result, "Call initiated successfully");
        } catch (ValidationException $e) {
            return $this->handleValidationError($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
