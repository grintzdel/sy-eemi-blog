<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Application\Services;

use App\Modules\Authentication\Application\Commands\RegisterUserCommand;
use App\Modules\Authentication\Application\UseCases\Commands\RegisterUserUseCase;
use App\Modules\User\Domain\Entities\UserEntity;

final readonly class AuthenticationService
{
    public function __construct(
        private RegisterUserUseCase $registerUserUseCase,
    ) {}

    public function registerUser(RegisterUserCommand $command): UserEntity
    {
        return $this->registerUserUseCase->execute($command);
    }
}
