<?php


namespace App\Services;

use App\Exceptions\CallNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CallService
{
    private const STATUSES = [
        'initiated',
        'ringing',
        'answered',
        'busy',
        'failed',
        'ended'
    ];

    private const FAILED_REASONS = [
        'network_error',
        'no_answer',
        'invalid_number',
        'service_unavailable'
    ];

    public function initiateCall(int $contactId): array
    {
        return [
            'message' => 'Call initiated.',
            'call_info' => [
                'call_id' => Str::random(10),
                'contact_id' => $contactId,
                'status' => 'initiated',
                'started_at' => Carbon::now()->toIso8601String()
            ]
        ];
    }

    public function pollCall(string $callId): array
    {
        // Simulate call not found (1 in 10 chance)
        if (rand(1, 10) === 1) {
            throw new CallNotFoundException($callId);
        }

        $startedAt = Carbon::now()->subSeconds(rand(1, 300));
        $status = self::STATUSES[rand(0, count(self::STATUSES) - 1)];

        $response = [
            'message' => 'Call status retrieved.',
            'call_info' => [
                'call_id' => $callId,
                'status' => $status,
                'started_at' => $startedAt->toIso8601String()
            ]
        ];

        // Add time_elapsed for answered calls
        if ($status === 'answered') {
            $response['call_info']['time_elapsed'] = rand(1, 120); // seconds
        }

        // Add duration for ended calls
        if ($status === 'ended') {
            $response['call_info']['duration'] = rand(10, 600); // seconds
        }

        // Add failure reason for failed calls
        if ($status === 'failed') {
            $response['call_info']['reason'] = self::FAILED_REASONS[array_rand(self::FAILED_REASONS)];
        }

        return $response;
    }
}
