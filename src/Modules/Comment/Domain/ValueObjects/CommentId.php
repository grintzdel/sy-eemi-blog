<?php

declare(strict_types=1);

namespace App\Modules\Comment\Domain\ValueObjects;

final readonly class CommentId
{
    public function __construct(
        private string $value
    ) {}

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
