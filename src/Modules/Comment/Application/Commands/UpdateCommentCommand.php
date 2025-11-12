<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\Commands;

use App\Modules\Comment\Domain\ValueObjects\CommentId;

final readonly class UpdateCommentCommand
{
    public function __construct(
        private CommentId $id,
        private string    $content,
    ) {}

    public function getId(): CommentId
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
