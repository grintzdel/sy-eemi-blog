<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Enums;

enum Roles: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';

    case ROLE_MODERATOR = 'ROLE_MODERATOR';

    case ROLE_USER = 'ROLE_USER';
}
