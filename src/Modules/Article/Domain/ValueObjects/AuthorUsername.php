<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class AuthorUsername implements \Stringable
{
    private function __construct(
        private string $username
    )
    {
        Assert::notEmpty($this->username, 'Author username cannot be empty');
        Assert::lengthBetween($this->username, 2, 20, 'Author username must be between 2 and 20 characters');
        Assert::regex($this->username, '/^[a-zA-Z0-9_]+$/', 'Author username can only contain letters, numbers and underscores');
    }

    public static function fromString(string $username): self
    {
        return new self($username);
    }

    public function getValue(): string
    {
        return $this->username;
    }

    public function equals(?self $other): bool
    {
        if($other === null)
        {
            return false;
        }

        return $this->username === $other->username;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->username;
    }
}
