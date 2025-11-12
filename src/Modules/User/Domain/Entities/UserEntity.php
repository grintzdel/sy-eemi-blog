<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Entities;

use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Domain\ValueObjects\Age;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\Password;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\Username;

final readonly class UserEntity
{
    public function __construct(
        private UserId   $id,
        private Username $username,
        private Email    $email,
        private Age      $age,
        private Password $password,
        private Roles    $role,
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

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getRole(): Roles
    {
        return $this->role;
    }
}
