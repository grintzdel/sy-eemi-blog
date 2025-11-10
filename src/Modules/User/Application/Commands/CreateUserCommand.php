<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Commands;

use App\Modules\User\Domain\ValueObjects\Age;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\Username;

final readonly class CreateUserCommand
{
    public function __construct(
        private UserId   $id,
        private Username $username,
        private Email    $email,
        private Age      $age,
    ) {}

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getAge(): Age
    {
        return $this->age;
    }
}
