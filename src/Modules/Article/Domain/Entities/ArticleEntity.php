<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Entities;

final readonly class ArticleEntity
{
    public function __construct(
        private string $id,
        private string $heading,
        private string $subheading,
        private string $content,
        private string $author,
    ) {}

    public function getId(): string
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
}
