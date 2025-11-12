<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Enums;

enum VoterActions: string
{
    case EDIT = 'EDIT';

    case DELETE = 'DELETE';
}
