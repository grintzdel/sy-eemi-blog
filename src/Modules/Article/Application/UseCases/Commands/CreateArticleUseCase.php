<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Application\Commands\CreateArticleCommand;
use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;

final readonly class CreateArticleUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository
    ) {}

    public function execute(CreateArticleCommand $command): ArticleEntity
    {
        try {
            $article = new ArticleEntity(
                $command->getId(),
                $command->getHeading(),
                $command->getSubheading(),
                $command->getContent(),
                $command->getAuthor(),
            );

            return $this->articleRepository->create($article);
        } catch (\Throwable $exception) {
            throw new ArticleDomainException($exception->getMessage());
        }
    }
}
