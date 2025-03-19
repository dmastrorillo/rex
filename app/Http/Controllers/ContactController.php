<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Traits\WithFlashMessages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{

    use WithFlashMessages;

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'firstName' => 'required',
            'surname' => 'required',
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^(\+61\d{9}|\+64\d{8,9})$/'],
        ]);
    }

    private function getSearchQuery(Request $request)
    {
        return $request->input('searchQuery') ?? $request->input("currentSearch");
    }

    private function redirectWithSearchQuery(string $route, Request $request)
    {
        return redirect()->route($route, ['searchQuery' => $this->getSearchQuery($request)]);
    }

    private function getContacts($request)
    {

        $search = $this->getSearchQuery($request);

        if (!$search) {
            return Contact::all();
        }

        return Contact::where('firstName', 'like', "%$search%")
            ->orWhere('lastName', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->get();
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

        $this->validateRequest($request);

        Contact::create($request->all());

        $this->flashSuccess($this->getSearchQuery($request) ? 'Contact created successfully, but it may not appear in the list due to your current search criteria.' : 'Contact created successfully');
        return $this->redirectWithSearchQuery('contacts', $request);
    }

    public function update(Request $request, Contact $contact)
    {

        $this->validateRequest($request);

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
