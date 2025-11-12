<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Commands;

use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\Username;

final readonly class RegisterUserCommand
{
    public function __construct(
        private UserId             $id,
        private Username           $username,
        private Email              $email,
        private \DateTimeImmutable $birthdate,
        private string             $plainPassword,
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

    public function getBirthdate(): \DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
