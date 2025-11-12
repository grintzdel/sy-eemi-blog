<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Application\Commands\UpdateArticleCommand;
use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\AuthorUsername;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\Username;

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

            $authorUsername = null;
            if($command->getAuthorUsername() !== null)
            {
                $username = new Username($command->getAuthorUsername());
                $user = $this->userRepository->findByUsername($username);

                if($user === null)
                {
                    throw new UserNotFoundException("User with username '{$command->getAuthorUsername()}' not found");
                }

                $authorUsername = AuthorUsername::fromString($command->getAuthorUsername());
            }

            $updatedArticle = $existingArticle->withUpdates(
                heading: $command->getHeading(),
                subheading: $command->getSubheading(),
                content: $command->getContent(),
                authorUsername: $authorUsername,
                coverImage: $command->getCoverImage(),
            );

            return $this->articleRepository->update($updatedArticle);
        } catch(\Throwable $exception)
        {
            throw new ArticleDomainException($exception->getMessage());
        }
    }
}
