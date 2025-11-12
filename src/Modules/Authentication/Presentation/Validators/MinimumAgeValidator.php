<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Presentation\Validators;

use App\Modules\User\Domain\Services\AgeVerifier;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class MinimumAgeValidator extends ConstraintValidator
{
    public function __construct(
        private readonly AgeVerifier $ageVerifier,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if(!$constraint instanceof MinimumAge)
        {
            throw new UnexpectedTypeException($constraint, MinimumAge::class);
        }

        if($value === null || $value === '')
        {
            return;
        }

        if(!$value instanceof \DateTimeImmutable)
        {
            throw new UnexpectedValueException($value, \DateTimeImmutable::class);
        }

        try
        {
            $this->ageVerifier->assertNotUnderage($value);
        } catch(\DomainException $e)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
