<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Doctrine\Entities;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'articles')]
class DoctrineArticleEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $heading;

    #[ORM\Column(type: 'string', length: 255)]
    private string $subheading;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'string', length: 255)]
    private string $author;

    public function __construct(
        string $id,
        string $heading,
        string $subheading,
        string $content,
        string $author
    )
    {
        $this->id = $id;
        $this->heading = $heading;
        $this->subheading = $subheading;
        $this->content = $content;
        $this->author = $author;
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

    public function getSubheading(): string
    {
        return $this->subheading;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function toDomain(): ArticleEntity
    {
        return new ArticleEntity(
            ArticleId::fromString($this->id),
            $this->heading,
            $this->subheading,
            $this->content,
            $this->author
        );
    }
}
