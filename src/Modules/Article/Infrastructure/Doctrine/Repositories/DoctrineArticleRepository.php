<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Doctrine\Repositories;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\Repositories\IArticleRepository;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
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
        $author = $this->entityManager->getRepository(DoctrineUserEntity::class)
            ->find($article->getAuthorId()->getValue());

        if($author === null)
        {
            throw UserNotFoundException::withId($article->getAuthorId()->getValue());
        }

        $doctrineArticle = new DoctrineArticleEntity(
            $article->getId()->getValue(),
            $article->getHeading(),
            $article->getSubheading(),
            $article->getContent(),
            $author,
            $article->getCoverImage()
        );

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

        $author = $this->entityManager->getRepository(DoctrineUserEntity::class)
            ->find($article->getAuthorId()->getValue());

        if($author === null)
        {
            throw UserNotFoundException::withId($article->getAuthorId()->getValue());
        }

        $doctrineArticle->setHeading($article->getHeading());
        $doctrineArticle->setSubheading($article->getSubheading());
        $doctrineArticle->setContent($article->getContent());
        $doctrineArticle->setAuthor($author);
        $doctrineArticle->setCoverImage($article->getCoverImage());
        $doctrineArticle->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return $doctrineArticle->toDomain();
    }

    public function delete(ArticleId $id): void
    {
        $doctrineArticle = $this->repository->find($id->getValue());

        if($doctrineArticle === null)
        {
            throw ArticleNotFoundException::withId($id->getValue());
        }

        $doctrineArticle->setDeletedAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }

    /*
     * Queries
     */
    public function findById(ArticleId $id): ArticleEntity
    {
        $qb = $this->repository->createQueryBuilder('a');
        $qb->join('a.author', 'u')
            ->addSelect('u')
            ->where('a.id = :id')
            ->andWhere('a.deletedAt IS NULL')
            ->setParameter('id', $id->getValue());

        $doctrineArticle = $qb->getQuery()->getOneOrNullResult();

        if($doctrineArticle === null)
        {
            throw ArticleNotFoundException::withId($id->getValue());
        }

        return $doctrineArticle->toDomain();
    }

    public function findAll(): array
    {
        $qb = $this->repository->createQueryBuilder('a');
        $qb->join('a.author', 'u')
            ->addSelect('u')
            ->where('a.deletedAt IS NULL')
            ->orderBy('a.createdAt', 'DESC');

        $doctrineArticles = $qb->getQuery()->getResult();

        return array_map(
            fn(DoctrineArticleEntity $doctrineArticle) => $doctrineArticle->toDomain(),
            $doctrineArticles
        );
    }

    /*
     * Presentation queries - return Doctrine entities with relations for ViewModels
     */
    public function findAllDoctrineEntities(): array
    {
        $qb = $this->repository->createQueryBuilder('a');
        $qb->join('a.author', 'u')
            ->addSelect('u')
            ->where('a.deletedAt IS NULL')
            ->orderBy('a.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findDoctrineEntityById(string $id): ?DoctrineArticleEntity
    {
        $qb = $this->repository->createQueryBuilder('a');
        $qb->join('a.author', 'u')
            ->addSelect('u')
            ->where('a.id = :id')
            ->andWhere('a.deletedAt IS NULL')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
