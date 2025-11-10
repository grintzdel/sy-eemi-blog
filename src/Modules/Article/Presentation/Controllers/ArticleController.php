<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig');
    }
}
