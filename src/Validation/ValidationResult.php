<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult
{
    /**
     * @param array<string, string> $errors
     * @param array<string, mixed>  $data
     */
    public function __construct(
        private readonly bool $valid,
        private readonly array $errors = [],
        private readonly array $data = [],
    ) {
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return array<string, string>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }
}