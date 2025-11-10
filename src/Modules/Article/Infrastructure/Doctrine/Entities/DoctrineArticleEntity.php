<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Doctrine\Entities;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'articles')]
class DoctrineArticleEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Heading is required')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Heading must be at least {{ limit }} characters long',
        maxMessage: 'Heading cannot be longer than {{ limit }} characters'
    )]
    private string $heading;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Subheading is required')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Subheading must be at least {{ limit }} characters long',
        maxMessage: 'Subheading cannot be longer than {{ limit }} characters'
    )]
    private string $subheading;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Content is required')]
    #[Assert\Length(
        min: 10,
        minMessage: 'Content must be at least {{ limit }} characters long'
    )]
    private string $content;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Author is required')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Author name must be at least {{ limit }} characters long',
        maxMessage: 'Author name cannot be longer than {{ limit }} characters'
    )]
    private string $author;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        string              $id,
        string              $heading,
        string              $subheading,
        string              $content,
        string              $author,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
        ?\DateTimeImmutable $deletedAt = null
    )
    {
        $this->id = $id;
        $this->heading = $heading;
        $this->subheading = $subheading;
        $this->content = $content;
        $this->author = $author;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
        $this->deletedAt = $deletedAt;
    }

    public static function fromDomain(ArticleEntity $article): self
    {
        return new self(
            $article->getId()->getValue(),
            $article->getHeading(),
            $article->getSubheading(),
            $article->getContent(),
            $article->getAuthor()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }

    public function setHeading(string $heading): void
    {
        $this->heading = $heading;
    }

    public function getSubheading(): string
    {
        return $this->subheading;
    }

    public function setSubheading(string $subheading): void
    {
        $this->subheading = $subheading;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
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

    public function toDomain(): ArticleEntity
    {
        return new ArticleEntity(
            ArticleId::fromString($this->id),
            $this->heading,
            $this->subheading,
            $this->content,
            $this->author,
            $this->createdAt,
            $this->updatedAt,
            $this->deletedAt
        );
    }
}
