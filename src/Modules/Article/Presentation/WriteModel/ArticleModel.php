<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\WriteModel;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use Symfony\Component\Validator\Constraints as Assert;

final class ArticleModel
{
    public function __construct(
        #[Assert\NotBlank(message: 'Heading is required')]
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Heading must be at least {{ limit }} characters long',
            maxMessage: 'Heading cannot be longer than {{ limit }} characters'
        )]
        public ?string $heading = null,

        #[Assert\NotBlank(message: 'Subheading is required')]
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Subheading must be at least {{ limit }} characters long',
            maxMessage: 'Subheading cannot be longer than {{ limit }} characters'
        )]
        public ?string $subheading = null,

        #[Assert\NotBlank(message: 'Content is required')]
        #[Assert\Length(
            min: 10,
            minMessage: 'Content must be at least {{ limit }} characters long'
        )]
        public ?string $content = null,

        #[Assert\NotBlank(message: 'Author is required')]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'Author name must be at least {{ limit }} characters long',
            maxMessage: 'Author name cannot be longer than {{ limit }} characters'
        )]
        public ?string $author = null,

        public mixed $coverImage = null,
    ) {}

    public static function createFromEntity(ArticleEntity $article): self
    {
        return new self(
            heading: $article->getHeading(),
            subheading: $article->getSubheading(),
            content: $article->getContent(),
            author: $article->getAuthor(),
        );
    }
}
