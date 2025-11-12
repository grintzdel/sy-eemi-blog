<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class UserId implements \Stringable
{
    private function __construct(
        private string $value
    )
    {
        Assert::notEmpty($value, 'User ID cannot be empty');
        Assert::uuid($value, 'User ID must be a valid UUID');
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public static function generate(): self
    {
        return new self(Uuid::v4()->toRfc4122());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
