<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\QueryException;
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
        $contact = Contact::findOrFail($data['id'])->first();
        $contact->delete();

        return $this->createOperationSuccessfulResponse($contact);
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
