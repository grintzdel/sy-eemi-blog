<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Presentation\Validators;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class MinimumAge extends Constraint
{
    public string $message = 'You must be at least 18 years old to register.';
}
