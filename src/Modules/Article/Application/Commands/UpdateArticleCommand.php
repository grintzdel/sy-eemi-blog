<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\Commands;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\User\Domain\ValueObjects\UserId;

final readonly class UpdateArticleCommand
{
    public function __construct(
        private ArticleId           $id,
        private ?string             $heading = null,
        private ?string             $subheading = null,
        private ?string             $content = null,
        private ?UserId             $authorId = null,
        private ?string             $coverImage = null,
        private ?\DateTimeImmutable $updatedAt = null,
    ) {}

    public function getId(): ArticleId
    {
        return $this->id;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getAuthorId(): ?UserId
    {
        return $this->authorId;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt ?? new \DateTimeImmutable();
    }
}
