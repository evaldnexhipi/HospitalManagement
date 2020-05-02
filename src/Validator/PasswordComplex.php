<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
class PasswordComplex extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Password-i te kete [A-Z]|[a-z]|[0-9]';
}
