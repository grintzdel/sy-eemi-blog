<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class ArticleId implements \Stringable
{
    private function __construct(
        private string $value
    )
    {
        Assert::notEmpty($value, 'Article ID cannot be empty');
        Assert::uuid($value, 'Article ID must be a valid UUID');
    }

    public static function fromString(string $id): self
    {
        return new self($id);
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
