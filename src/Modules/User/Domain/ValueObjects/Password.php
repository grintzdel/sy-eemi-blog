<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Password implements \Stringable
{
    public string $value;

    private function __construct(string $value)
    {
        Assert::notEmpty($value);

        $this->value = $value;
    }

    public static function fromPlain(string $plainPassword): self
    {
        Assert::minLength($plainPassword, 8, 'Password must be at least 8 characters long');

        return new self($plainPassword);
    }

    public static function fromHashed(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }
}
