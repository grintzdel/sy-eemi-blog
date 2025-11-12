<?php

declare(strict_types=1);

namespace App\Modules\Comment\Domain\Repositories;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Comment\Domain\ValueObjects\CommentId;

interface ICommentRepository
{
    /*
     * Commands
     */
    public function create(CommentEntity $comment): CommentEntity;

    public function update(CommentEntity $comment): CommentEntity;

    public function delete(CommentId $id): void;

    /*
     * Queries
     */
    public function findById(CommentId $id): CommentEntity;

    public function findByArticleId(ArticleId $articleId): array;

    public function findDoctrineCommentsByArticleId(ArticleId $articleId): array;

    public function findAll(): array;
}
