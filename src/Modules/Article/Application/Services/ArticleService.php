<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\Services;

use App\Modules\Article\Application\Commands\CreateArticleCommand;
use App\Modules\Article\Application\Commands\UpdateArticleCommand;
use App\Modules\Article\Application\UseCases\Commands\CreateArticleUseCase;
use App\Modules\Article\Application\UseCases\Commands\DeleteArticleUseCase;
use App\Modules\Article\Application\UseCases\Commands\UpdateArticleUseCase;
use App\Modules\Article\Application\UseCases\Queries\FindAllArticlesUseCase;
use App\Modules\Article\Application\UseCases\Queries\FindArticleByIdUseCase;
use App\Modules\Article\Domain\Entities\ArticleEntity;

final readonly class ArticleService
{
    public function __construct(
        private CreateArticleUseCase   $createArticleUseCase,
        private FindArticleByIdUseCase $findArticleByIdUseCase,
        private FindAllArticlesUseCase $findAllArticlesUseCase,
        private UpdateArticleUseCase   $updateArticleUseCase,
        private DeleteArticleUseCase   $deleteArticleUseCase,
    ) {}

    /*
     * Commands
     */
    public function create(CreateArticleCommand $article): ArticleEntity
    {
        return $this->createArticleUseCase->execute($article);
    }

    public function update(UpdateArticleCommand $article): ArticleEntity
    {
        return $this->updateArticleUseCase->execute($article);
    }

    public function delete(string $id): void
    {
        $this->deleteArticleUseCase->execute($id);
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
