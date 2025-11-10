<?php

declare(strict_types=1);

namespace App\Modules\Article\Domain\Repositories;

use App\Modules\Article\Domain\Entities\ArticleEntity;

interface IArticleRepository
{
    public function create(ArticleEntity $article): ArticleEntity;
}
