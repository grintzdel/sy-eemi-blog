<?php

declare(strict_types=1);

namespace App\Modules\Shared\Presentation\Controllers;

use App\Modules\Article\Infrastructure\Doctrine\Repositories\DoctrineArticleRepository;
use App\Modules\Article\Presentation\ViewModels\ArticleViewModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AppController
{
    public function __construct(
        private readonly DoctrineArticleRepository $articleRepository,
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $doctrineArticles = $this->articleRepository->findAllDoctrineEntities();
        $articles = array_map(
            static fn($article) => ArticleViewModel::fromDoctrineEntity($article),
            $doctrineArticles
        );

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
