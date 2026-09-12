<?php


namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

final class SalleValidator implements SalleValidatorInterface
{
    private const TYPES_AUTORISES = ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'];

    public function validate(array $data): ValidationResult
    {
        $rules = [
            'nom'      => v::stringType()->length(2, 100),
            'batiment' => v::stringType()->length(2, 100),
            'capacite' => v::intVal()->between(1, 1000),
            'type'     => v::in(self::TYPES_AUTORISES),
            'active'   => v::boolVal(),
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