<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\Services;

use App\Modules\Comment\Application\Commands\CreateCommentCommand;
use App\Modules\Comment\Application\UseCases\Commands\CreateCommentUseCase;
use App\Modules\Comment\Application\UseCases\Commands\DeleteCommentUseCase;
use App\Modules\Comment\Application\UseCases\Queries\FindCommentByIdUseCase;
use App\Modules\Comment\Application\UseCases\Queries\FindCommentsByArticleIdUseCase;
use App\Modules\Comment\Domain\Entities\CommentEntity;

final readonly class CommentService
{
    public function __construct(
        private CreateCommentUseCase           $createCommentUseCase,
        private DeleteCommentUseCase           $deleteCommentUseCase,
        private FindCommentByIdUseCase         $findCommentByIdUseCase,
        private FindCommentsByArticleIdUseCase $findCommentsByArticleIdUseCase,
    ) {}

    /*
     * Commands
     */
    public function create(CreateCommentCommand $comment): CommentEntity
    {
        return $this->createCommentUseCase->execute($comment);
    }

    public function delete(string $id): void
    {
        $this->deleteCommentUseCase->execute($id);
    }

    /*
     * Queries
     */
    public function findById(string $id): CommentEntity
    {
        return $this->findCommentByIdUseCase->execute($id);
    }

    public function findByArticleId(string $articleId): array
    {
        return $this->findCommentsByArticleIdUseCase->execute($articleId);
    }
}
