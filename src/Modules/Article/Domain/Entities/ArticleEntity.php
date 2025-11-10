<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Entities;

use App\Modules\Article\Domain\ValueObjects\ArticleId;

final readonly class ArticleEntity
{
    public function __construct(
        private ArticleId $id,
        private string $heading,
        private string $subheading,
        private string $content,
        private string $author,
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

    public function withUpdates(
        ?string $heading = null,
        ?string $subheading = null,
        ?string $content = null,
        ?string $author = null,
    ): self {
        return new self(
            $this->id,
            $heading ?? $this->heading,
            $subheading ?? $this->subheading,
            $content ?? $this->content,
            $author ?? $this->author,
        );
    }
}
