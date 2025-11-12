<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases\Queries;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Comment\Domain\Repositories\ICommentRepository;

final readonly class FindCommentsByArticleIdUseCase
{
    public function __construct(
        private ICommentRepository $commentRepository
    ) {}

    public function execute(string $articleId): array
    {
        $articleIdVO = ArticleId::fromString($articleId);

        return $this->commentRepository->findByArticleId($articleIdVO);
    }
}
