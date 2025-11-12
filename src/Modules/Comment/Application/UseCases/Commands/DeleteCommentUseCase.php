<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases\Commands;

use App\Modules\Comment\Domain\Repositories\ICommentRepository;
use App\Modules\Comment\Domain\ValueObjects\CommentId;

final readonly class DeleteCommentUseCase
{
    public function __construct(
        private ICommentRepository $commentRepository,
    ) {}

    public function execute(string $id): void
    {
        $commentId = CommentId::fromString($id);

        $this->commentRepository->delete($commentId);
    }
}
