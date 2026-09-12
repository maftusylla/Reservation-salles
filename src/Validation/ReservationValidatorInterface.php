<?php


namespace App\Validation;

interface ReservationValidatorInterface
{
    public function validate(array $data): ValidationResult;
}