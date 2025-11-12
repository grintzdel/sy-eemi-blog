<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Application\Commands\UpdateArticleCommand;
use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;

final readonly class UpdateArticleUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository,
        private IUserRepository    $userRepository
    ) {}

    public function execute(UpdateArticleCommand $command): ArticleEntity
    {
        try
        {
            $existingArticle = $this->articleRepository->findById($command->getId());

            $authorId = null;
            if($command->getAuthorId() !== null)
            {
                $user = $this->userRepository->findById($command->getAuthorId());

                if($user === null)
                {
                    throw new UserNotFoundException("User with ID '{$command->getAuthorId()->getValue()}' not found");
                }

                $authorId = $command->getAuthorId();
            }

            $updatedArticle = $existingArticle->withUpdates(
                heading: $command->getHeading(),
                subheading: $command->getSubheading(),
                content: $command->getContent(),
                authorId: $authorId,
                coverImage: $command->getCoverImage(),
            );

            return $this->articleRepository->update($updatedArticle);
        } catch(\Throwable $exception)
        {
            throw new ArticleDomainException($exception->getMessage());
        }
    }
}
