<?php

declare(strict_types=1);

namespace App\Modules\User\Application\UseCases\Queries;

use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\Username;

final readonly class FindUserByUsernameUseCase
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function execute(string $username): ?UserEntity
    {
        return $this->userRepository->findByUsername(new Username($username));
    }
}
