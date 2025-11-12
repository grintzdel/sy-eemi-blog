<?php

declare(strict_types=1);

namespace App\Modules\Comment\Infrastructure\Doctrine\Repositories;

use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Comment\Domain\Exceptions\CommentNotFoundException;
use App\Modules\Comment\Domain\Repositories\ICommentRepository;
use App\Modules\Comment\Domain\ValueObjects\CommentId;
use App\Modules\Comment\Infrastructure\Doctrine\Entities\DoctrineCommentEntity;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineCommentRepository implements ICommentRepository
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(DoctrineCommentEntity::class);
    }

    /*
     * Commands
     */
    public function create(CommentEntity $comment): CommentEntity
    {
        $article = $this->entityManager->getRepository(DoctrineArticleEntity::class)
            ->find($comment->getArticleId()->getValue());

        if($article === null)
        {
            throw ArticleNotFoundException::withId($comment->getArticleId()->getValue());
        }

        $author = $this->entityManager->getRepository(DoctrineUserEntity::class)
            ->find($comment->getAuthorId()->getValue());

        if($author === null)
        {
            throw UserNotFoundException::withId($comment->getAuthorId()->getValue());
        }

        $doctrineComment = new DoctrineCommentEntity(
            $comment->getId()->getValue(),
            $comment->getContent(),
            $article,
            $author
        );

        $this->entityManager->persist($doctrineComment);
        $this->entityManager->flush();

        return $doctrineComment->toDomain();
    }

    public function update(CommentEntity $comment): CommentEntity
    {
        $doctrineComment = $this->repository->find($comment->getId()->getValue());

        if($doctrineComment === null)
        {
            throw CommentNotFoundException::withId($comment->getId()->getValue());
        }

        $doctrineComment->setContent($comment->getContent());
        $doctrineComment->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return $doctrineComment->toDomain();
    }

    public function delete(CommentId $id): void
    {
        $doctrineComment = $this->repository->find($id->getValue());

        if($doctrineComment === null)
        {
            throw CommentNotFoundException::withId($id->getValue());
        }

        $doctrineComment->setDeletedAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }

    /*
     * Queries
     */
    public function findById(CommentId $id): CommentEntity
    {
        $doctrineComment = $this->repository->findOneBy([
            'id' => $id->getValue(),
            'deletedAt' => null
        ]);

        if($doctrineComment === null)
        {
            throw CommentNotFoundException::withId($id->getValue());
        }

        return $doctrineComment->toDomain();
    }

    public function findByArticleId(ArticleId $articleId): array
    {
        $qb = $this->repository->createQueryBuilder('c');
        $qb->join('c.article', 'a')
            ->where('a.id = :articleId')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('articleId', $articleId->getValue())
            ->orderBy('c.createdAt', 'DESC');

        $doctrineComments = $qb->getQuery()->getResult();

        return array_map(
            fn(DoctrineCommentEntity $doctrineComment) => $doctrineComment->toDomain(),
            $doctrineComments
        );
    }

    public function findDoctrineCommentsByArticleId(ArticleId $articleId): array
    {
        $qb = $this->repository->createQueryBuilder('c');
        $qb->join('c.article', 'a')
            ->join('c.author', 'u')
            ->where('a.id = :articleId')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('articleId', $articleId->getValue())
            ->orderBy('c.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findAll(): array
    {
        $doctrineComments = $this->repository->findBy(
            ['deletedAt' => null],
            ['createdAt' => 'DESC']
        );

        return array_map(
            fn(DoctrineCommentEntity $doctrineComment) => $doctrineComment->toDomain(),
            $doctrineComments
        );
    }
}
