<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\ArticleId;

final readonly class DeleteArticleUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository,
    ) {}

    public function execute(string $id): void
    {
        $articleId = ArticleId::fromString($id);

        $this->articleRepository->delete($articleId);
    }
}
