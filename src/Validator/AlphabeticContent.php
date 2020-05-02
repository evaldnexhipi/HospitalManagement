<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Doctrine\Common\Annotations\Annotation\Target;
/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
class AlphabeticContent extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Permbajtja te kete shkronja';
}
