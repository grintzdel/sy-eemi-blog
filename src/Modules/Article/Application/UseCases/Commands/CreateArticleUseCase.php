<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Application\Commands\CreateArticleCommand;
use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\AuthorUsername;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\Username;

final readonly class CreateArticleUseCase
{
    public function __construct(
        private IArticleRepository $articleRepository,
        private IUserRepository    $userRepository
    ) {}

    public function execute(CreateArticleCommand $command): ArticleEntity
    {
        try
        {
            $createdAt = $command->getCreatedAt();

            $username = new Username($command->getAuthorUsername());
            $user = $this->userRepository->findByUsername($username);

            if($user === null)
            {
                throw new UserNotFoundException("User with username '{$command->getAuthorUsername()}' not found");
            }

            $authorUsername = AuthorUsername::fromString($command->getAuthorUsername());

            $article = new ArticleEntity(
                $command->getId(),
                $command->getHeading(),
                $command->getSubheading(),
                $command->getContent(),
                $authorUsername,
                $command->getCoverImage(),
                $createdAt,
                $createdAt,
            );

            return $this->articleRepository->create($article);
        } catch(\Throwable $exception)
        {
            throw new ArticleDomainException($exception->getMessage());
        }
    }
}
