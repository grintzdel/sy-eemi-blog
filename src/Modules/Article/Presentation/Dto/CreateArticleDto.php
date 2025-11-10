<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Dto;

final readonly class CreateArticleDto
{
    public function __construct(
        private string $heading,
        private string $subheading,
        private string $content,
        private string $author,
    ) {}

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
