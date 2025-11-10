<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Repositories\IArticleRepository;

final readonly class CreateArticleUseCase
{
    public function __construct(
        private readonly IArticleRepository $repository
    ) {}

    public function execute(ArticleEntity $article): ArticleEntity
    {
        return $this->repository->create($article);
    }
}
