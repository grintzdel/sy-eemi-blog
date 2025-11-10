<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Exceptions;

final class ArticleNotFoundException extends ArticleDomainException
{
    public static function withId(string $id): self
    {
        return new self(sprintf('Article with ID "%s" not found', $id));
    }
}
