<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            Contact::factory()->count(10000)->create();
            $this->command->info('Successfully created 10000 contacts');
        } catch (\Exception $e) {
            $this->command->error('Error creating contacts: ' . $e->getMessage());
        }
    }
}
