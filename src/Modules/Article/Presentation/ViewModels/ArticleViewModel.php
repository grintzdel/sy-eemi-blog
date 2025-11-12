<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\ViewModels;

use App\Modules\Article\Domain\Entities\ArticleEntity;

final readonly class ArticleViewModel
{
    public function __construct(
        public string $id,
        public string $heading,
        public string $subheading,
        public string $content,
        public string $authorUsername,
        public ?string $coverImage,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}

    public static function fromEntity(ArticleEntity $article, string $authorUsername): self
    {
        return new self(
            id: $article->getId()->getValue(),
            heading: $article->getHeading(),
            subheading: $article->getSubheading(),
            content: $article->getContent(),
            authorUsername: $authorUsername,
            coverImage: $article->getCoverImage(),
            createdAt: $article->getCreatedAt(),
            updatedAt: $article->getUpdatedAt(),
        );
    }
}