<?php

declare(strict_types=1);

namespace App\Modules\User\Application\UseCases\Commands;

use App\Modules\User\Application\Commands\CreateUserCommand;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\Exceptions\UserDomainException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\Services\AgeVerifier;
use App\Modules\User\Domain\Services\EmailVerifier;
use App\Modules\User\Domain\ValueObjects\Age;

final readonly class CreateUserUseCase
{
    public function __construct(
        private IUserRepository $userRepository,
        private EmailVerifier   $emailVerifier,
        private AgeVerifier     $ageVerifier,
    ) {}

    public function execute(CreateUserCommand $command): UserEntity
    {
        try
        {
            $this->emailVerifier->assertNotUsed($command->getEmail());

            $this->ageVerifier->assertNotUnderage($command->getBirthdate());

            $age = Age::fromDateTime($command->getBirthdate());

            $user = new UserEntity(
                $command->getId(),
                $command->getUsername(),
                $command->getEmail(),
                $age
            );

            return $this->userRepository->create($user);
        } catch(\Throwable $exception)
        {
            throw new UserDomainException($exception->getMessage());
        }
    }
}
