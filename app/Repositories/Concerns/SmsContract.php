<?php

declare(strict_types=1);

namespace App\Repositories\Concerns;

interface SmsContract
{
    /**
     * Sends a message to a user's phone number.
     *
     * @param  string  $phone  The phone number to send the message to.
     * @param  string  $message  The message to send.
     * @param  string  $hash  Optional parameter used for additional security.
     *
     * @return bool Returns true if the message was sent successfully, false otherwise.
     */
    public function send(string $phone, string $message, string $hash = ''): bool;
}
