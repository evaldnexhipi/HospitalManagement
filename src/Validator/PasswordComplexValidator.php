<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordComplexValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value) {
            if (!preg_match('/[0-9]/', $value, $matches) || // number
                !preg_match('/[a-z]/', $value, $matches) || // lowercase character
                !preg_match('/[A-Z]/', $value, $matches)    // uppercase character
            ) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
