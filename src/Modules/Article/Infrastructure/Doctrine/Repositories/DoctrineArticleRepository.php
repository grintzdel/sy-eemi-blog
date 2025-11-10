<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Doctrine\Repositories;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineArticleRepository implements IArticleRepository
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(DoctrineArticleEntity::class);
    }

    /*
     * Commands
     */
    public function create(ArticleEntity $article): ArticleEntity
    {
        $doctrineArticle = DoctrineArticleEntity::fromDomain($article);

        $this->entityManager->persist($doctrineArticle);
        $this->entityManager->flush();

        return $doctrineArticle->toDomain();
    }

    public function update(ArticleEntity $article): ArticleEntity
    {
        $doctrineArticle = $this->repository->find($article->getId()->getValue());

        if($doctrineArticle === null)
        {
            throw ArticleNotFoundException::withId($article->getId()->getValue());
        }

        $doctrineArticle->setHeading($article->getHeading());
        $doctrineArticle->setSubheading($article->getSubheading());
        $doctrineArticle->setContent($article->getContent());
        $doctrineArticle->setAuthor($article->getAuthor());

        $this->entityManager->flush();

        return $doctrineArticle->toDomain();
    }

    public function delete(ArticleId $id): void
    {
        $doctrineArticle = $this->repository->find($id->getValue());

        if ($doctrineArticle === null) {
            throw ArticleNotFoundException::withId($id->getValue());
        }

        $this->entityManager->remove($doctrineArticle);
        $this->entityManager->flush();
    }

    /*
     * Queries
     */
    public function findById(ArticleId $id): ArticleEntity
    {
        $doctrineArticle = $this->repository->find($id->getValue());

        if($doctrineArticle === null)
        {
            throw ArticleNotFoundException::withId($id->getValue());
        }

        return $doctrineArticle->toDomain();
    }

    public function findAll(): array
    {
        $doctrineArticles = $this->repository->findAll();

        return array_map(
            fn(DoctrineArticleEntity $doctrineArticle) => $doctrineArticle->toDomain(),
            $doctrineArticles
        );
    }
}
