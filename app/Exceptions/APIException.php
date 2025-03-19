<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class APIException extends Exception
{

    const ERROR_TYPES = [
        "VALIDATION_ERROR" => 422,
        "MODEL_NOT_FOUND" => 404,
        "ROUTE_NOT_FOUND" => 404,
        "AUTHENTICATION_FAILED" => 401,
        "PERMISSION_DENIED" => 403,
        "RATE_LIMIT_EXCEEDED" => 429,
        "UNEXPECTED_ERROR" => 500,
        "METHOD_NOT_ALLOWED" => 405
    ];

    protected int $status;
    protected string $type;
    protected array $details;
    protected array $fixes;
    protected ?string $documentationUrl;
    protected string $traceId;

    public function __construct(
        string $message,
        string $errorType,
        array $details = [],
        array $fixes = [],
        ?string $documentationUrl = null
    ) {

        $status = self::ERROR_TYPES[$errorType] ?? 500;
        $type = $errorType;



        parent::__construct($message);
        $this->status = $status;
        $this->type = $type;
        $this->details = $details;
        $this->fixes = $fixes;
        $this->documentationUrl = $documentationUrl;
        $this->traceId = request()->attributes->get("trace_id") ?? uniqid();
    }

    /**
     * Convert exception to a structured API response.
     */
    public function render()
    {
        return response()->json($this->toResponse(), $this->status);
    }


    public function toResponse()
    {
        return [
            "success" => false,
            "error" => [
                "status" => $this->status,
                "type" => $this->type,
                "message" => $this->getMessage(),
                "details" => $this->details,
                "fixes" => $this->fixes,
                "trace_id" => $this->traceId,
                "documentation_url" => $this->documentationUrl
            ]
        ];
    }

    /**
     * Create a new API exception for a validation error.
     */
    public static function createValidationError(ValidationException $e)
    {

        return new APIException(
            "Validation failed for the provided input.",
            "VALIDATION_ERROR",
            $e->errors(),
            ["Ensure all fields meet validation rules."],
            "https://api.example.com/docs/errors/validation"
        );
    }

    /**
     * Create a ModelNotFoundException API exception.
     */
    public static function createModelNotFound(ModelNotFoundException $e)
    {
        return new APIException(
            "The requested resource was not found.",
            "MODEL_NOT_FOUND",
            ["model" => class_basename($e->getModel())],
            ["Ensure the ID is correct."],
            "https://api.example.com/docs/errors/not-found"
        );
    }

    /**
     * Create Route Not Found API exception.
     */
    public static function createRouteNotFound(Request $request)
    {
        return new APIException(
            "The requested route was not found.",
            "ROUTE_NOT_FOUND",
            ["url" => $request->path()],
            ["Ensure the route is correct."],
            "https://api.example.com/docs/errors/not-found"
        );
    }

    /**
     * Create an API exception for an authentication failure.
     */
    public static function createAuthenticationFailed(AuthenticationException $e)
    {
        return new APIException(
            "Authentication failed.",
            "AUTHENTICATION_FAILED",
            [],
            ["Include a valid Bearer token in the Authorization header.", "Ensure the token is not expired. Refresh it if necessary."],
            "https://api.example.com/docs/errors/authentication"
        );
    }

    /**
     * Create an API exception for a permission denied error.
     */
    public static function createPermissionDenied()
    {
        return new APIException(
            "You do not have permission to access this resource.",
            "PERMISSION_DENIED",
            [],
            ["Ensure you have the necessary permissions to perform this action.", "Contact an administrator for assistance."],
            "https://api.example.com/docs/errors/permission-denied"
        );
    }

    /**
     * Create an API exception for a rate limit exceeded error.
     */
    public static function createRateLimitExceeded()
    {
        return new APIException(
            "You have exceeded the allowed number of requests.",
            "RATE_LIMIT_EXCEEDED",
            [],
            ["Slow down the rate of requests.", "Wait before making another request."],
            "https://api.example.com/docs/errors/rate-limit"
        );
    }

    /**
     * Create an API exception for a method not allowed error.
     */
    public static function createMethodNotAllowed(Request $request)
    {
        return new APIException(
            "The requested method is not allowed for this route.",
            "METHOD_NOT_ALLOWED",
            ["method" => $request->method()],
            ["Ensure you are using the correct HTTP method for this route."],
            "https://api.example.com/docs/errors/method-not-allowed"
        );
    }

    /**
     * Create an API exception for an unexpected error.
     */
    public static function createUnexpectedError(Exception $e)
    {
        return new APIException(
            "An unexpected error occurred.",
            "UNEXPECTED_ERROR",
            ["message" => $e->getMessage(), "exception" => get_class($e)],
            ["Contact support for assistance."],
            "https://api.example.com/docs/errors/unexpected"
        );
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getFixes()
    {
        return $this->fixes;
    }

    public function getDocumentationUrl()
    {
        return $this->documentationUrl;
    }

    public function getTraceId()
    {
        return $this->traceId;
    }
}
