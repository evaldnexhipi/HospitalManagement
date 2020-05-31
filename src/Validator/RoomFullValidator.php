<?php

namespace App\Validator;

use App\Repository\RoomRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RoomFullValidator extends ConstraintValidator
{
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value) {
            if ($value->getCapacity()-$value->getNumberOfPatients()<1)
            {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
