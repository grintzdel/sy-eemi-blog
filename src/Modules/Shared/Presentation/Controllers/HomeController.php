<?php

declare(strict_types=1);

namespace App\Modules\Shared\Presentation\Controllers;

use App\Modules\Article\Application\Services\ArticleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AppController
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $articles = $this->articleService->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
