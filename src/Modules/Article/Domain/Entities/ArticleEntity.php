<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Entities;

use App\Modules\Article\Domain\ValueObjects\ArticleId;

final readonly class ArticleEntity
{
    public function __construct(
        private ArticleId           $id,
        private string              $heading,
        private string              $subheading,
        private string              $content,
        private string              $author,
        private ?string             $coverImage,
        private \DateTimeImmutable  $createdAt,
        private \DateTimeImmutable  $updatedAt,
        private ?\DateTimeImmutable $deletedAt = null,
    ) {}

    public function getId(): ArticleId
    {
        return $this->id;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }

    public function getSubheading(): string
    {
        return $this->subheading;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
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
        ?string $heading = null,
        ?string $subheading = null,
        ?string $content = null,
        ?string $author = null,
        ?string $coverImage = null,
    ): self
    {
        return new self(
            $this->id,
            $heading ?? $this->heading,
            $subheading ?? $this->subheading,
            $content ?? $this->content,
            $author ?? $this->author,
            $coverImage ?? $this->coverImage,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->deletedAt,
        );
    }
}
