<?php

declare(strict_types=1);

namespace App\Modules\User\Application\UseCases\Commands;

use App\Modules\User\Application\Commands\CreateUserCommand;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\Exceptions\UserDomainException;
use App\Modules\User\Domain\Repositories\IUserRepository;

final readonly class CreateUserUseCase
{
    public function __construct(
        private IUserRepository $userRepository,
    ) {}

    public function execute(CreateUserCommand $command): UserEntity
    {
        try
        {
            $user = new UserEntity(
                $command->getId(),
                $command->getUsername(),
                $command->getEmail(),
                $command->getAge()
            );

            return $this->userRepository->create($user);
        } catch(\Throwable $exception)
        {
            throw new UserDomainException($exception->getMessage());
        }
    }
}
