<?php

declare(strict_types=1);

namespace App\Modules\Comment\Presentation\ViewModels;

use App\Modules\Comment\Infrastructure\Doctrine\Entities\DoctrineCommentEntity;

final readonly class CommentViewModel
{
    public function __construct(
        public int                 $id,
        public string              $content,
        public string              $authorUsername,
        public int                 $authorId,
        public \DateTimeImmutable  $createdAt,
        public \DateTimeImmutable  $updatedAt,
    ) {}

    public static function fromDoctrineEntity(DoctrineCommentEntity $comment): self
    {
        return new self(
            id: $comment->getId(),
            content: $comment->getContent(),
            authorUsername: $comment->getAuthor()->getUsername(),
            authorId: $comment->getAuthor()->getId(),
            createdAt: $comment->getCreatedAt(),
            updatedAt: $comment->getUpdatedAt(),
        );
    }
}