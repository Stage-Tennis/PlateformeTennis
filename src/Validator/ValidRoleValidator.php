<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Roles;

class ValidRoleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $roles = Roles::cases();
        $validRoles = [];

        foreach ($roles as $role) {
            $validRoles[] = $role->value;
        }

        if (!in_array($value["role"], $validRoles)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
