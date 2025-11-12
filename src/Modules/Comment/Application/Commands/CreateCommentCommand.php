<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\Commands;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Comment\Domain\ValueObjects\CommentId;
use App\Modules\User\Domain\ValueObjects\UserId;

final readonly class CreateCommentCommand
{
    public function __construct(
        private CommentId           $id,
        private string              $content,
        private ArticleId           $articleId,
        private UserId              $authorId,
        private ?\DateTimeImmutable $createdAt = null,
    ) {}

    public function getId(): CommentId
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getArticleId(): ArticleId
    {
        return $this->articleId;
    }

    public function getAuthorId(): UserId
    {
        return $this->authorId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt ?? new \DateTimeImmutable();
    }
}
