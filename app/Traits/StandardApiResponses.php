<?php

namespace App\Traits;

use App\Exceptions\APIException;
use App\Exceptions\CallNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Pest\Laravel\instance;

trait StandardApiResponses
{


    public function successResponse($data, $code = 200)
    {
        return response()->json(["success" => true, "trace_id" => request()->attributes->get("trace_id"), "data" => $data], $code);
    }

    public function handleException(\Exception $exception)
    {
        // Convert APIException directly
        if ($exception instanceof APIException) {
            throw $exception;
        }

        // Handle Model Not Found (Eloquent)
        if ($exception instanceof ModelNotFoundException) {
            throw APIException::createModelNotFound($exception);
        }

        // Handle Authentication Errors (401)
        if ($exception instanceof AuthenticationException) {
            throw APIException::createAuthenticationFailed($exception);
        }

        // Handle Authorization Errors (403)
        if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
            throw APIException::createPermissionDenied();
        }

        // Handle Validation Errors (422)
        if ($exception instanceof ValidationException) {
            throw APIException::createValidationError($exception);
        }

        // Handle Rate Limiting (429)
        if ($exception instanceof ThrottleRequestsException) {
            throw APIException::createRateLimitExceeded($exception);
        }

        //Handle CallNotFound Exception
        if ($exception instanceof CallNotFoundException) {
            throw APIException::createCallNotFound($exception);
        }

        // Fallback for all other exceptions
        throw APIException::createUnexpectedError($exception);
    }
}
