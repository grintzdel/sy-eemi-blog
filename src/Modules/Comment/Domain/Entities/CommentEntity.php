<?php

declare(strict_types=1);

namespace App\Modules\Comment\Domain\Entities;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Comment\Domain\ValueObjects\CommentId;
use App\Modules\User\Domain\ValueObjects\UserId;

final readonly class CommentEntity
{
    public function __construct(
        private CommentId           $id,
        private string              $content,
        private ArticleId           $articleId,
        private UserId              $authorId,
        private \DateTimeImmutable  $createdAt,
        private \DateTimeImmutable  $updatedAt,
        private ?\DateTimeImmutable $deletedAt = null,
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
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function withUpdates(
        ?string $content = null,
    ): self
    {
        return new self(
            $this->id,
            $content ?? $this->content,
            $this->articleId,
            $this->authorId,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->deletedAt,
        );
    }
}
