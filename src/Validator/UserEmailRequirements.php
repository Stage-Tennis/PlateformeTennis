<?php

namespace App\Validator\Constraints;

use App\Validator\EmailNotAlreadyUsed;
use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

#[\Attribute]
class UserEmailRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Email(),
            new EmailNotAlreadyUsed(),
        ];
    }
}
