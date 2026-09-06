<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'salle_id'    => v::intVal()->positive(),
            'responsable' => v::stringType()->length(2, 120),
            'email'       => v::email(),
            'motif'       => v::stringType()->length(5, 255),
            'date_debut'  => v::date(),
            'date_fin'    => v::date(),
        ];

        $errors = [];

        foreach ($rules as $champ => $validator) {
            try {
                $validator->assert($data[$champ] ?? null);
            } catch (NestedValidationException $exception) {
                $errors[$champ] = $exception->getMessages()[0] ?? "Le champ {$champ} est invalide.";
            }
        }

        if ($errors !== []) {
            return new ValidationResult(valid: false, errors: $errors);
        }

        return new ValidationResult(valid: true, data: $data);
    }
}