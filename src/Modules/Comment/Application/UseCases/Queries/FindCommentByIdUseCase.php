<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases\Queries;

use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Comment\Domain\Repositories\ICommentRepository;
use App\Modules\Comment\Domain\ValueObjects\CommentId;

final readonly class FindCommentByIdUseCase
{
    public function __construct(
        private ICommentRepository $commentRepository
    ) {}

    public function execute(string $id): CommentEntity
    {
        $commentId = CommentId::fromString($id);

        return $this->commentRepository->findById($commentId);
    }
}