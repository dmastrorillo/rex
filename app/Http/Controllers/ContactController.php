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

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    private function getSearchQuery(Request $request)
    {
        return $request->input('searchQuery');
    }

    private function redirectWithSearchQuery(string $route, Request $request)
    {
        return redirect()->route($route, ['searchQuery' => $this->getSearchQuery($request)]);
    }

    private function getContacts(Request $request)
    {
        $search = $this->getSearchQuery($request);

        if (!$search) {
            return Contact::paginate(10);
        }



        return Contact::where('firstName', 'like', "%$search%")
            ->orWhere('lastName', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")->paginate(10)->appends([
                'searchQuery' => $search,
            ]);
    }


    public function show(Request $request)
    {

        return Inertia::render('contacts/index', [
            'contacts' =>  $this->getContacts($request),
            'searchQuery' => $this->getSearchQuery($request)
        ]);
    }

    public function store(Request $request)
    {
        $this->contactService->createContact($request->all());


        $this->flashSuccess(
            $this->getSearchQuery($request)
                ? 'Contact created successfully, but it may not appear in the list due to your current search criteria.'
                : 'Contact created successfully'
        );

        return $this->redirectWithSearchQuery('contacts', $request);
    }

    public function update(Request $request, Contact $contact)
    {

        $contact->update($request->all());

        $this->flashSuccess('Contact updated successfully');

        return $this->redirectWithSearchQuery('contacts', $request);
    }

    public function destroy(Contact $contact, Request $request)
    {

        $contact->delete();

        $this->flashSuccess('Contact deleted successfully');
        return $this->redirectWithSearchQuery('contacts', $request);
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

        return redirect()->route('contacts')->with('outcome', $outcomes[$outcome]);
    }
}
