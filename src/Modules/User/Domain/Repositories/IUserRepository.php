<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Repositories;

use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\UserId;

interface IUserRepository
{
    /*
     * Commands
     */
    public function create(UserEntity $user): UserEntity;

    /*
     * Queries
     */
    public function findById(UserId $id): UserEntity;

    public function findByEmail(Email $email): ?UserEntity;
}
