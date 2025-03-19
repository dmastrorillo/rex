<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\ContactService;
use App\Traits\WithFlashMessages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{

    use WithFlashMessages;

    /**
     * @var ContactService
     */
    protected $contactService;

    public function __construct(\App\Services\ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    private function redirectWithSearchQuery(string $route, Request $request)
    {
        return redirect()->route($route, ['searchQuery' => $this->contactService->getSearchQuery($request)]);
    }


    public function index(Request $request)
    {
        $searchQuery = $this->contactService->getSearchQuery($request);

        return Inertia::render('contacts/index', [
            'contacts' =>  $this->contactService->getContacts($searchQuery, null),
            'searchQuery' => $searchQuery
        ]);
    }

    public function store(Request $request)
    {
        $this->contactService->createContact($request->all());


        $this->flashSuccess(
            $this->contactService->getSearchQuery($request)
                ? 'Contact created successfully, but it may not appear in the list due to your current search criteria.'
                : 'Contact created successfully'
        );

        return $this->redirectWithSearchQuery('contacts.index', $request);
    }

    public function show(Contact $contact)
    {
        return Inertia::render('contacts/view', [
            'contact' => $contact
        ]);
    }

    public function update(Request $request, Contact $contact)
    {

        $contact->update($request->all());

        $this->flashSuccess('Contact updated successfully');

        return  redirect()->route('contacts.show', ['contact' => $contact->id]);
    }

    public function destroy(Contact $contact, Request $request)
    {

        $contact->delete();

        $this->flashSuccess('Contact deleted successfully');
        return $this->redirectWithSearchQuery('contacts.index', $request);
    }

    public function call(Contact $contact)
    {

        //Mocked

        $outcomes = [
            'success' => 'Call was successful',
            'busy' => 'The contact is busy',
            'no-answer' => 'The contact did not answer',
            'failed' => 'The call failed'
        ];

        $outcome = array_rand($outcomes);
        sleep(2);

        return redirect()->route('contacts.index')->with('outcome', $outcomes[$outcome]);
    }
}
