<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DBTransactions
{
    /**
     * Execute a callback inside a database transaction
     *
     * @param callable $callback
     * @param int|null $retries Number of retries if a deadlock occurs
     * @return mixed
     */
    protected function transaction(callable $callback, ?int $retries = 3)
    {
        return DB::transaction($callback, $retries);
    }

    /**
     * Check if we are currently inside a transaction
     */
    protected function inTransaction(): bool
    {
        return DB::transactionLevel() > 0;
    }
}
