<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\UseCases\Commands;

use App\Modules\Authentication\Application\Commands\RegisterUserCommand;
use App\Modules\Authentication\Domain\Services\IPasswordHasher;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Application\Commands\CreateUserCommand;
use App\Modules\User\Application\UseCases\Commands\CreateUserUseCase;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\ValueObjects\Password;

final readonly class RegisterUserUseCase
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private IPasswordHasher   $passwordHasher,
    ) {}

    public function execute(RegisterUserCommand $command): UserEntity
    {
        $plainPassword = Password::fromPlain($command->getPlainPassword());
        $hashedPassword = $this->passwordHasher->hash($plainPassword);

        $createUserCommand = new CreateUserCommand(
            $command->getId(),
            $command->getUsername(),
            $command->getEmail(),
            $command->getBirthdate(),
            $hashedPassword,
            $command->getRole()
        );

        return $this->createUserUseCase->execute($createUserCommand);
    }
}
