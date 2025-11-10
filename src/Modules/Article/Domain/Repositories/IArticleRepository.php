<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Repositories;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\ValueObjects\ArticleId;

interface IArticleRepository
{
    /*
     * Commands
     */
    public function create(ArticleEntity $article): ArticleEntity;

    public function update(ArticleEntity $article): ArticleEntity;

    public function delete(ArticleId $id): void;

    /*
     * Queries
     */
    public function findById(ArticleId $id): ArticleEntity;

    public function findAll(): array;
}
