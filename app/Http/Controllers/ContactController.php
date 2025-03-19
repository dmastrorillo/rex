<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Traits\WithFlashMessages;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        $this->validateRequest($request);

        try {
            Contact::create($request->all());
            $this->flashSuccess($this->getSearchQuery($request) ? 'Contact created successfully, but it may not appear in the list due to your current search criteria.' : 'Contact created successfully');
            return $this->redirectWithSearchQuery('contacts', $request);
        } catch (QueryException $e) {
            $code = $e->getCode();
            $message = strtolower($e->getMessage());
            if (str_starts_with($code, '23') && str_contains($message, 'unique')) {
                if (str_contains($message, 'contacts.email')) {
                    throw ValidationException::withMessages([
                        'email' => ['This email address is already registered in the system.']
                    ]);
                }
                if (str_contains($message, 'contacts.phone')) {
                    throw ValidationException::withMessages([
                        'phone' => ['This phone number is already registered in the system.']
                    ]);
                }
            }
            // Rethrow unknown exceptions for now
            throw $e;
        }
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
