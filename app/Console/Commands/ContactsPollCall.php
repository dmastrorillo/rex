<?php

namespace App\Console\Commands;

use App\Console\Traits\InteractiveCommandTrait;
use App\Services\CallService;
use App\Services\ContactService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class ContactsPollCall extends Command
{
    use InteractiveCommandTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:poll-call
                            {--id= : The ID of the call you want to poll}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll a call via the command line';

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
                'prompt' => 'Call ID',
                'description' => 'The ID of the Call you want to poll',
                'required' => true
            ],
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Polling call');

        $data = $this->collectFieldValues();

        try {
            $result = $this->callService->pollCall($data['id']);

            return $this->handleResult($result, "Call polled successfully");
        } catch (ValidationException $e) {
            return $this->handleValidationError($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
