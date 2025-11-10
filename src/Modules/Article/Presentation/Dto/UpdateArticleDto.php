<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Dto;

final readonly class UpdateArticleDto
{
    public function __construct(
        private string  $id,
        private ?string $heading = null,
        private ?string $subheading = null,
        private ?string $content = null,
        private ?string $author = null,
    ) {}

    public function getId(): string
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }
}
