<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Doctrine\Repositories;

use App\Modules\User\Domain\Repositories\IUserRepository;

final readonly class DoctrineUserRepository implements IUserRepository
{
    public function __construct() {}
}
