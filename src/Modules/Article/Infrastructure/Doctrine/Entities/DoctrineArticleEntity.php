<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Doctrine\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'doctrine_article_entity')]
final readonly class DoctrineArticleEntity
{
    public function __construct() {}
}
