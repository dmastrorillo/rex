<?php

namespace App\Services;

use App\Models\Contact;
use App\Traits\DBTransactions;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContactService
{


    use DBTransactions;

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
     * @return Contact Created contact
     * @throws ValidationException|QueryException|\Exception
     */
    public function createContact(array $data)
    {
        $rules = [
            'firstName' => 'required',
            'surname' => 'required',
            'email' => ['required', 'email', 'unique:contacts,email'],
            'phone' => ['required', 'regex:/^(\+61\d{9}|\+64\d{8,9})$/', 'unique:contacts,phone'],
        ];

        try {

            $data = $this->validate($data, $rules);

            $fn = function () use ($data) {
               return Contact::create($data);
            };

            return $this->inTransaction() ? $fn() : $this->transaction($fn);
        } catch (QueryException $e) {
            $this->handleQueryException($e);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a contact from the database
     * @param int $id Contact ID
     * @return int Contact ID of deleted contact
     */
    public function deleteContact(Contact $contact)
    {

        $fn = function () use ($contact) {
            $contact->delete();
        };

        $this->inTransaction() ? $fn()  : $this->transaction($fn);


        return $contact->id;
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
     * Get a contact from the database
     * @param array $data Contact ID
     * @return Contact Contact
     */
    public function getContact($data)
    {

        $rules = [
            'id' => 'required|exists:contacts,id'
        ];

        try {
            $validated = $this->validate($data, $rules);
            return Contact::findOrFail($validated['id']);
        } catch (QueryException $e) {
            $this->handleQueryException($e);
        } catch (\Exception $e) {
            throw $e;
        }
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
            'id' => 'required|exists:contacts,id',
            'firstName' => 'sometimes',
            'surname' => 'sometimes',
            'email' => ['sometimes', 'nullable', 'email', 'unique:contacts,email,' . $id],
            'phone' => ['sometimes', 'nullable', 'regex:/^(\+61\d{9}|\+64\d{8,9})$/', 'unique:contacts,phone,' . $id],
        ];

        $data['id'] = $id;

        try {
            $data = $this->validate($data, $rules);
            unset($data['id']);

            $fn = function () use ($data, $id) {
                $contact = Contact::findOrFail($id);
                $contact->update($data);
                return $contact;
            };

            return $this->inTransaction() ? $fn() : $this->transaction($fn);
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
