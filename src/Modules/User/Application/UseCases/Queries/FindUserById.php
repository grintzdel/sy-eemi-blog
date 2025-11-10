<?php

declare(strict_types=1);

namespace App\Modules\User\Application\UseCases\Queries;

use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\UserId;

final readonly class FindUserById
{
    public function __construct(
        private IUserRepository $userRepository,
    ) {}

    public function execute(string $id): UserEntity
    {
        $userId = UserId::fromString($id);

        return $this->userRepository->findById($userId);
    }
}
