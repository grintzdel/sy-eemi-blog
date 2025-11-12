<?php

declare(strict_types=1);

namespace App\Modules\Article\Application\UseCases\Commands;

use App\Modules\Article\Application\Commands\CreateArticleCommand;
use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;

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

            // Verify user exists
            $user = $this->userRepository->findById($command->getAuthorId());

            if($user === null)
            {
                throw new UserNotFoundException("User with ID '{$command->getAuthorId()->getValue()}' not found");
            }

            $article = new ArticleEntity(
                $command->getId(),
                $command->getHeading(),
                $command->getSubheading(),
                $command->getContent(),
                $command->getAuthorId(),
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
