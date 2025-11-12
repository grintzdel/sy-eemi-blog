<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases\Commands;

use App\Modules\Comment\Application\Commands\CreateCommentCommand;
use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Comment\Domain\Repositories\ICommentRepository;
use App\Modules\Comment\Domain\ValueObjects\CommentId;

final readonly class CreateCommentUseCase
{
    public function __construct(
        private ICommentRepository $commentRepository
    ) {}

    public function execute(CreateCommentCommand $command): CommentEntity
    {
        $createdAt = $command->getCreatedAt();

        $comment = new CommentEntity(
            $command->getId(),
            $command->getContent(),
            $command->getArticleId(),
            $command->getAuthorId(),
            $createdAt,
            $createdAt,
        );

        return $this->commentRepository->create($comment);
    }
}
