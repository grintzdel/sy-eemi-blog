<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

final readonly class Age implements \Stringable
{
    public int $value;

    private function __construct(int $value)
    {
        Assert::greaterThan($value, 0);

        $this->value = $value;
    }

    public function greaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public static function from(int $age): self
    {
        return new self($age);
    }

    public static function fromDateTime(\DateTimeImmutable $birthdate): self
    {
        $age = new \DateTimeImmutable()->diff($birthdate)->y;

        return new self($age);
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value == $other->value;
    }

    public function lowerThen(self $other): bool
    {
        return $this->value < $other->value;
    }
}
