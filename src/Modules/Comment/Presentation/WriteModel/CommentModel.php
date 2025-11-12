<?php

declare(strict_types=1);

namespace App\Modules\Comment\Presentation\WriteModel;

use App\Modules\Comment\Domain\Entities\CommentEntity;
use Symfony\Component\Validator\Constraints as Assert;

final class CommentModel
{
    public function __construct(
        #[Assert\NotBlank(message: 'Comment content is required')]
        #[Assert\Length(
            min: 1,
            max: 2000,
            minMessage: 'Comment must be at least {{ limit }} character long',
            maxMessage: 'Comment cannot be longer than {{ limit }} characters'
        )]
        public ?string $content = null,

        public ?string $articleId = null,
    ) {}

    public static function createFromEntity(CommentEntity $comment): self
    {
        return new self(
            content: $comment->getContent(),
            articleId: $comment->getArticleId()->getValue(),
        );
    }
}
