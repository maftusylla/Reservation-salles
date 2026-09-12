<?php


namespace App\Exception;

use RuntimeException;

final class ReservationIntrouvableException extends RuntimeException
{
    public function __construct(
        string $message,
    private readonly int $code404 = 404,
    ) {
        parent::__construct($message);
    }

    public function code(): int
    {
        return $this->code404;
    }

}
