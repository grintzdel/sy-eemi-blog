<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\Commands;

use App\Modules\Article\Domain\ValueObjects\ArticleId;

final readonly class CreateArticleCommand
{
    public function __construct(
        private ArticleId $id,
        private string    $heading,
        private string    $subheading,
        private string    $content,
        private string    $author,
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
}
