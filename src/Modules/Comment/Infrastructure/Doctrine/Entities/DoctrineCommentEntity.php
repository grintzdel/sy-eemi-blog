<?php

declare(strict_types=1);

namespace App\Modules\Comment\Infrastructure\Doctrine\Entities;

use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Comment\Domain\ValueObjects\CommentId;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'comments')]
class DoctrineCommentEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Comment content is required')]
    #[Assert\Length(
        min: 1,
        max: 2000,
        minMessage: 'Comment must be at least {{ limit }} character long',
        maxMessage: 'Comment cannot be longer than {{ limit }} characters'
    )]
    private string $content;

    #[ORM\ManyToOne(targetEntity: DoctrineArticleEntity::class)]
    #[ORM\JoinColumn(name: 'article_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: 'Article is required')]
    private DoctrineArticleEntity $article;

    #[ORM\ManyToOne(targetEntity: DoctrineUserEntity::class)]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: 'Author is required')]
    private DoctrineUserEntity $author;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        string                $id,
        string                $content,
        DoctrineArticleEntity $article,
        DoctrineUserEntity    $author,
        ?\DateTimeImmutable   $createdAt = null,
        ?\DateTimeImmutable   $updatedAt = null,
        ?\DateTimeImmutable   $deletedAt = null
    )
    {
        $this->id = $id;
        $this->content = $content;
        $this->article = $article;
        $this->author = $author;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
        $this->deletedAt = $deletedAt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getArticle(): DoctrineArticleEntity
    {
        return $this->article;
    }

    public function setArticle(DoctrineArticleEntity $article): void
    {
        $this->article = $article;
    }

    public function getAuthor(): DoctrineUserEntity
    {
        return $this->author;
    }

    public function setAuthor(DoctrineUserEntity $author): void
    {
        $this->author = $author;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function toDomain(): CommentEntity
    {
        return new CommentEntity(
            CommentId::fromString($this->id),
            $this->content,
            ArticleId::fromString($this->article->getId()),
            UserId::fromString($this->author->getId()),
            $this->createdAt,
            $this->updatedAt,
            $this->deletedAt
        );
    }

    public function getId(): string
    {
        return $this->id;
    }
}
