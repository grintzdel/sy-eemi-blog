<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Queries;

use App\Modules\Article\Domain\Repositories\IArticleRepository;

final readonly class FindAllArticlesUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository
    ) {}

    public function execute(): array
    {
        return $this->articleRepository->findAll();
    }
}
