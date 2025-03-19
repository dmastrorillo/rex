<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContactService
{

    protected function createOperationSuccessfulResponse($response)
    {
        return [
            'success' => true,
            'response' => $response
        ];
    }

    public function getSearchQuery(Request $request)
    {
        return $request->input('searchQuery');
    }

    /**
     * Validate the contact data and return the validated data
     *
     * @param array $data
     * @return array Validated data
     * @throws ValidationException
     */
    protected function validate(array $data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Create a new contact in the database
     *
     * @param array $data contact data
     * @return array Response with status and messages
     */
    public function createContact(array $data)
    {
        $rules = [
            'firstName' => 'required',
            'surname' => 'required',
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^(\+61\d{9}|\+64\d{8,9})$/'],
        ];

        try {

            $data = $this->validate($data, $rules);

            $contact = Contact::create($data);

            return $this->createOperationSuccessfulResponse($contact);
        } catch (QueryException $e) {
            $this->handleQueryException($e);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a contact from the database
     * @param int $id Contact ID
     * @return array Response with status and messages
     * @throws ValidationException
     */
    public function deleteContact($id)
    {

        $rules = [
            'id' => 'required|exists:contacts,id'
        ];


        $data = $this->validate(['id' => $id], $rules);
        $contact = Contact::destroy($data['id']);

        return $this->createOperationSuccessfulResponse($contact);
    }

    /**
     * Get contacts from the database
     * @param string|null $searchQuery Search query
     * @param int|null $page Page number (default: null)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getContacts(?string $searchQuery, ?int $page = null)
    {
        $query = Contact::query();

        if ($searchQuery) {
            $query->whereRaw(
                '(firstName LIKE ? OR surname LIKE ? OR email LIKE ? OR phone LIKE ?)',
                array_fill(0, 4, "%$searchQuery%")
            );
        }

        // For web requests, Laravel will automatically handle the page from the request
        // For CLI, we'll use the explicitly provided page number
        return $query->paginate(10, ['*'], 'page', $page)->appends(['searchQuery' => $searchQuery]);
    }

    /**
     * Update a contact in the database
     * @param array $data Contact data
     * @param int $id Contact ID
     * @return array Response with status and messages
     * @throws ValidationException
     */
    public function updateContact(array $data, $id)
    {
        $rules = [
            'firstName' => 'sometimes',
            'surname' => 'sometimes',
            'email' => ['sometimes', 'nullable', 'email'],
            'phone' => ['sometimes', 'nullable', 'regex:/^(\+61\d{9}|\+64\d{8,9})$/'],
        ];

        try {
            $data = $this->validate($data, $rules);

            $contact = Contact::findOrFail($id);
            $contact->update($data);

            return $this->createOperationSuccessfulResponse($contact);
        } catch (QueryException $e) {
            $this->handleQueryException($e);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle query exceptions, particularly for unique constraint violations
     *
     * @param QueryException $e
     * @return void
     * @throws ValidationException
     */
    protected function handleQueryException(QueryException $e)
    {
        $code = $e->getCode();
        $message = strtolower($e->getMessage());

        if (str_starts_with($code, '23') && str_contains($message, 'unique')) {
            if (str_contains($message, 'contacts.email')) {
                $error = 'This email address is already registered in the system.';

                throw ValidationException::withMessages([
                    'email' => [$error]
                ]);
            }

            if (str_contains($message, 'contacts.phone')) {
                $error = 'This phone number is already registered in the system.';

                throw ValidationException::withMessages([
                    'phone' => [$error]
                ]);
            }
        }
        throw $e;
    }
}
