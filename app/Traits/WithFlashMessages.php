<?php

namespace App\Traits;

trait WithFlashMessages
{
    protected function flashSuccess(string $message): void
    {
        $this->flash('success', $message);
    }

    protected function flashError(string $message): void
    {
        $this->flash('error', $message);
    }

    protected function flashWarning(string $message): void
    {
        $this->flash('warning', $message);
    }

    protected function flashInfo(string $message): void
    {
        $this->flash('info', $message);
    }

    protected function flash(string $type, string $message): void
    {
        session()->flash('message', [
            'type' => $type,
            'content' => $message
        ]);
    }
}
