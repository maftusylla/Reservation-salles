<?php


namespace App\Validation;

interface SalleValidatorInterface
{
    public function validate(array $data): ValidationResult;
}