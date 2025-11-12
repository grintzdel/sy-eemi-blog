<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Entities;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Domain\ValueObjects\AuthorUsername;

final readonly class ArticleEntity
{
    public function __construct(
        private ArticleId           $id,
        private string              $heading,
        private string              $subheading,
        private string              $content,
        private AuthorUsername      $authorUsername,
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

    public function getAuthorUsername(): AuthorUsername
    {
        return $this->authorUsername;
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
        ?string         $heading = null,
        ?string         $subheading = null,
        ?string         $content = null,
        ?AuthorUsername $authorUsername = null,
        ?string         $coverImage = null,
    ): self
    {
        return new self(
            $this->id,
            $heading ?? $this->heading,
            $subheading ?? $this->subheading,
            $content ?? $this->content,
            $authorUsername ?? $this->authorUsername,
            $coverImage ?? $this->coverImage,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->deletedAt,
        );
    }
}
