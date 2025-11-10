<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use App\Modules\User\Domain\ValueObjects\Email;

final class EmailAlreadyUsed extends \DomainException
{
    public static function with(Email $email): self
    {
        return new self(sprintf('Email %s is already used', $email));
    }
}
