<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases\Commands;

use App\Modules\Comment\Application\Commands\UpdateCommentCommand;
use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Comment\Domain\Repositories\ICommentRepository;

final readonly class UpdateCommentUseCase
{
    public function __construct(
        private ICommentRepository $commentRepository
    ) {}

    public function execute(UpdateCommentCommand $command): CommentEntity
    {
        $comment = $this->commentRepository->findById($command->getId());

        $updatedComment = $comment->withUpdates(
            content: $command->getContent()
        );

        return $this->commentRepository->update($updatedComment);
    }
}
