<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Services;

use App\Modules\User\Domain\Exceptions\EmailAlreadyUsed;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\Email;

final readonly class EmailVerifier
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function assertNotUsed(Email $email): void
    {
        $used = $this->userRepository->findByEmail($email);

        if($used !== null)
        {
            throw EmailAlreadyUsed::with($email);
        }
    }
}
