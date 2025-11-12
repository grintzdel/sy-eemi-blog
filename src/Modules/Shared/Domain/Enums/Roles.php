<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Enums;

enum Roles: string
{
    case ADMIN = 'ADMIN';

    case MODERATOR = 'MODERATOR';

    case USER = 'USER';
}
