<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Doctrine\Repositories;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineArticleRepository implements IArticleRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function create(ArticleEntity $article): ArticleEntity
    {
        $doctrineArticle = DoctrineArticleEntity::fromDomain($article);

        $this->entityManager->persist($doctrineArticle);
        $this->entityManager->flush();

        return $doctrineArticle->toDomain();
    }

    public function findById(ArticleId $id): ArticleEntity
    {
        $repository = $this->entityManager->getRepository(DoctrineArticleEntity::class);
        $doctrineArticle = $repository->find($id->getValue());

        if($doctrineArticle === null)
        {
            throw ArticleNotFoundException::withId($id->getValue());
        }

        return $doctrineArticle->toDomain();
    }
}
