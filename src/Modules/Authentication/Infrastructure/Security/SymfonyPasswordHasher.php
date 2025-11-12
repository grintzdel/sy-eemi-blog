<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Infrastructure\Security;

use App\Modules\Authentication\Domain\Services\IPasswordHasher;
use App\Modules\User\Domain\ValueObjects\Password;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class SymfonyPasswordHasher implements IPasswordHasher
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function hash(Password $plainPassword): Password
    {
        $tempUser = new DoctrineUserEntity();

        $hashedValue = $this->passwordHasher->hashPassword(
            $tempUser,
            $plainPassword->value
        );

        return Password::fromHashed($hashedValue);
    }

    public function verify(Password $plainPassword, Password $hashedPassword): bool
    {
        $tempUser = new DoctrineUserEntity();
        $tempUser->setPasswordForVerification($hashedPassword->value);

        return $this->passwordHasher->isPasswordValid(
            $tempUser,
            $plainPassword->value
        );
    }
}
