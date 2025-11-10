<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\Services;

use App\Modules\Article\Application\UseCases\Commands\CreateArticleUseCase;
use App\Modules\Article\Application\UseCases\Queries\FindAllArticlesUseCase;
use App\Modules\Article\Application\UseCases\Queries\FindArticleByIdUseCase;
use App\Modules\Article\Domain\Entities\ArticleEntity;

final readonly class ArticleService
{
    public function __construct(
        private CreateArticleUseCase   $createArticleUseCase,
        private FindArticleByIdUseCase $findArticleByIdUseCase,
        private FindAllArticlesUseCase $findAllArticlesUseCase,
    ) {}

    /*
     * Commands
     */
    public function create(ArticleEntity $article): ArticleEntity
    {
        return $this->createArticleUseCase->execute($article);
    }

    /*
     * Queries
     */
    public function findById(string $id): ArticleEntity
    {
        return $this->findArticleByIdUseCase->execute($id);
    }

    public function findAll(): array
    {
        return $this->findAllArticlesUseCase->execute();
    }
}
