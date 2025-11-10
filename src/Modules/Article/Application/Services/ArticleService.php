<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\Services;

use App\Modules\Article\Application\UseCases\Commands\CreateArticleUseCase;
use App\Modules\Article\Domain\Entities\ArticleEntity;

final readonly class ArticleService
{
    public function __construct(
        private readonly CreateArticleUseCase $createArticleUseCase,
    ) {}

    public function create(ArticleEntity $article): ArticleEntity
    {
        return $this->createArticleUseCase->execute($article);
    }
}
