<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

final class UserNotFoundException extends UserExceptionDomain
{
    public static function withId(string $id): self
    {
        return new self(sprintf('User with ID "%s" not found', $id));
    }
}
