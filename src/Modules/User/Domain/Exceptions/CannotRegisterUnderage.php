<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use App\Modules\User\Domain\ValueObjects\Age;

final class CannotRegisterUnderage extends \DomainException
{
    public static function with(Age $age): self
    {
        return new self(sprintf('Cannot register underage user, age %d is not allowed', $age->value));
    }
}
