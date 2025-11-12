<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Domain\Services;

use App\Modules\User\Domain\ValueObjects\Password;

interface IPasswordHasher
{
    public function hash(Password $plainPassword): Password;

    public function verify(Password $plainPassword, Password $hashedPassword): bool;
}
