<?php

declare(strict_types=1);

namespace App\Modules\Comment\Domain\Exceptions;

final class CommentNotFoundException extends \Exception
{
    public static function withId(int $id): self
    {
        return new self(sprintf('Comment with id "%d" not found', $id));
    }
}
