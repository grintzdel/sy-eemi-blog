<?php

declare(strict_types=1);

namespace App\Modules\User\Application\UseCases\Queries;

use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\Email;

final readonly class FindUserByEMailUseCase
{
    public function __construct(
        private IUserRepository $userRepository,
    ) {}

    public function execute(string $email): ?UserEntity
    {
        $userEmail = new Email($email);

        return $this->userRepository->findByEmail($userEmail);
    }
}
