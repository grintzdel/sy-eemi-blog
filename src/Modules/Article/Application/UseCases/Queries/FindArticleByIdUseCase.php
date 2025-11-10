<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Queries;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\ArticleId;

final readonly class FindArticleByIdUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository
    ) {}

    public function execute(string $id): ArticleEntity
    {
        $articleId = ArticleId::fromString($id);

        return $this->articleRepository->findById($articleId);
    }
}
