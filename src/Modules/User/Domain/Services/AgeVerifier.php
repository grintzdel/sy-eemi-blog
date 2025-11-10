<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Services;

use App\Modules\User\Domain\Exceptions\CannotRegisterUnderage;
use App\Modules\User\Domain\ValueObjects\Age;

final readonly class AgeVerifier
{
    private const int MAJORITY_THRESHOLD = 18;

    public function assertNotUnderage(\DateTimeImmutable $birthdate): void
    {
        $age = Age::fromDateTime($birthdate);
        $majority = Age::from(self::MAJORITY_THRESHOLD);

        if($age->lowerThen($majority) && !$age->equals($majority))
        {
            throw CannotRegisterUnderage::with($age);
        }
    }
}
