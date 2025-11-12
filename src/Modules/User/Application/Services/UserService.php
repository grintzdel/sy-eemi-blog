<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Services;

use App\Modules\User\Application\Commands\CreateUserCommand;
use App\Modules\User\Application\UseCases\Commands\CreateUserUseCase;
use App\Modules\User\Application\UseCases\Queries\FindUserByEMailUseCase;
use App\Modules\User\Application\UseCases\Queries\FindUserByIdUseCase;
use App\Modules\User\Application\UseCases\Queries\FindUserByUsernameUseCase;
use App\Modules\User\Domain\Entities\UserEntity;

final readonly class UserService
{
    public function __construct(
        private CreateUserUseCase         $createUserUseCase,
        private FindUserByIdUseCase       $findUserByIdUseCase,
        private FindUserByEMailUseCase    $findUserByEMailUseCase,
        private FindUserByUsernameUseCase $findUserByUsernameUseCase,
    ) {}

    /*
     * Commands
     */
    public function create(CreateUserCommand $user): UserEntity
    {
        return $this->createUserUseCase->execute($user);
    }

    /*
     * Queries
     */
    public function findById(string $id): UserEntity
    {
        return $this->findUserByIdUseCase->execute($id);
    }

    public function findByEmail(string $email): ?UserEntity
    {
        return $this->findUserByEMailUseCase->execute($email);
    }

    public function findByUsername(string $username): ?UserEntity
    {
        return $this->findUserByUsernameUseCase->execute($username);
    }
}
