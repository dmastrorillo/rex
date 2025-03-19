<?php

namespace App\Exceptions;

use Exception;

class CallNotFoundException extends Exception
{
    protected string $callId;

    public function __construct(string $callId)
    {
        $this->callId = $callId;
        parent::__construct("Call ID {$callId} not found.");
    }

    public function getCallId(): string
    {
        return $this->callId;
    }
}
