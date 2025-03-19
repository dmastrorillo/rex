<?php

namespace App\Console\Commands;

use App\Console\Traits\InteractiveCommandTrait;
use App\Services\ContactService;
use Illuminate\Console\Command;

class ContactsList extends Command
{

    use InteractiveCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:list
                        {--search= : Optional search query to filter contacts}
                        {--page=1 : Page number for paginated results}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List contacts with optional search and pagination';

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
                'name' => 'search',
                'option' => 'search',
                'prompt' => 'Search query',
                'description' => 'Optional search query to filter contacts',
                'required' => false
            ],
            [
                'name' => 'page',
                'option' => 'page',
                'prompt' => 'Page number',
                'description' => 'Page number for results',
                'required' => false
            ]
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = $this->collectFieldValues();

        $contacts = $this->contactService->getContacts($data['search'], $data['page']);

        if ($contacts->isEmpty()) {
            $this->info('No contacts found.');
            return 1;
        }


        $this->table(
            ['ID', 'First Name', 'Surname', 'Email', 'Phone'],
            $contacts->map(function ($contact) {
                return [
                    $contact->id,
                    $contact->firstName,
                    $contact->surname,
                    $contact->email,
                    $contact->phone
                ];
            })
        );

        $this->info(sprintf(
            "\nShowing page %d of %d (Total records: %d)",
            $contacts->currentPage(),
            $contacts->lastPage(),
            $contacts->total()
        ));

        if ($data['search']) {
            $this->info(sprintf('Search query: "%s"', $data['search']));
        }
    }
}
