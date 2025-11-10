<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Application\Commands\UpdateArticleCommand;
use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;

final readonly class UpdateArticleUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository
    ) {}

    public function execute(UpdateArticleCommand $command): ArticleEntity
    {
        try
        {
            $existingArticle = $this->articleRepository->findById($command->getId());

            $updatedArticle = $existingArticle->withUpdates(
                heading: $command->getHeading(),
                subheading: $command->getSubheading(),
                content: $command->getContent(),
                author: $command->getAuthor(),
            );

            return $this->articleRepository->update($updatedArticle);
        } catch(\Throwable $exception)
        {
            throw new ArticleDomainException($exception->getMessage());
        }
    }
}
