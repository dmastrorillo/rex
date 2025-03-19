<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Services\ContactService;
use App\Services\CallService;

class ContactApiController extends Controller
{

    use \App\Traits\StandardApiResponses;

    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * @var CallService
     */
    protected $callService;

    public function __construct(\App\Services\ContactService $contactService, \App\Services\CallService $callService)
    {
        $this->contactService = $contactService;
        $this->callService = $callService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->successResponse($this->contactService->getContacts($this->contactService->getSearchQuery($request)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $contact = $this->contactService->createContact($request->all());
            return $this->successResponse($contact, 201)->header('Location', route('contacts.show', ['contact' => $contact->id]));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact, Request $request)
    {
        try {
            return $this->successResponse($contact);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        try {
            return $this->successResponse($this->contactService->updateContact($request->all(), $contact->id));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        try {
           return $this->successResponse(["id " => $this->contactService->deleteContact($contact)]);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Call a contact
     */
    public function initiateCall(Contact $contact)
    {
        try {
            return $this->successResponse($this->callService->initiateCall($contact->id));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Poll a call
     */
    public function pollCall(string $callId)
    {
        try {
            return $this->successResponse($this->callService->pollCall($callId));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
