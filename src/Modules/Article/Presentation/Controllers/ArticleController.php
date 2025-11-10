<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Controllers;

use App\Modules\Article\Application\Commands\CreateArticleCommand;
use App\Modules\Article\Application\Commands\UpdateArticleCommand;
use App\Modules\Article\Application\Services\ArticleService;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Presentation\Dto\CreateArticleDto;
use App\Modules\Article\Presentation\Dto\UpdateArticleDto;
use App\Modules\Article\Presentation\Forms\ArticleFormType;
use App\Modules\Shared\Presentation\Controllers\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/articles')]
final class ArticleController extends AppController
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {}

    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(): Response
    {
        $articles = $this->articleService->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return $this->handleForm(
            request: $request,
            form: $this->createForm(ArticleFormType::class),
            onSuccess: function(array $data)
            {
                $dto = new CreateArticleDto(
                    heading: $data['heading'],
                    subheading: $data['subheading'],
                    content: $data['content'],
                    author: $data['author'],
                );

                $command = new CreateArticleCommand(
                    id: ArticleId::fromString(Uuid::v4()->toString()),
                    heading: $dto->getHeading(),
                    subheading: $dto->getSubheading(),
                    content: $dto->getContent(),
                    author: $dto->getAuthor(),
                );

                $this->articleService->create($command);

                return $this->successRedirect('Article créé avec succès !', 'article_index');
            },
            template: 'article/new.html.twig',
            exceptionHandlers: [
                ArticleDomainException::class => [
                    'message' => 'Erreur lors de la création',
                    'type' => 'error'
                ]
            ]
        );
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($id, $request)
            {
                $article = $this->articleService->findById($id);

                return $this->handleForm(
                    request: $request,
                    form: $this->createForm(ArticleFormType::class, [
                        'heading' => $article->getHeading(),
                        'subheading' => $article->getSubheading(),
                        'content' => $article->getContent(),
                        'author' => $article->getAuthor(),
                    ]),
                    onSuccess: function(array $data) use ($id)
                    {
                        $dto = new UpdateArticleDto(
                            id: $id,
                            heading: $data['heading'],
                            subheading: $data['subheading'],
                            content: $data['content'],
                            author: $data['author'],
                        );

                        $command = new UpdateArticleCommand(
                            id: ArticleId::fromString($dto->getId()),
                            heading: $dto->getHeading(),
                            subheading: $dto->getSubheading(),
                            content: $dto->getContent(),
                            author: $dto->getAuthor(),
                        );

                        $this->articleService->update($command);

                        return $this->successRedirect('Article modifié avec succès !', 'article_index');
                    },
                    template: 'article/edit.html.twig',
                    templateData: ['article' => $article],
                    exceptionHandlers: [
                        ArticleDomainException::class => [
                            'message' => 'Erreur lors de la modification',
                            'type' => 'error'
                        ]
                    ]
                );
            },
            exceptionHandlers: [
                ArticleNotFoundException::class => [
                    'message' => 'Article non trouvé',
                    'type' => 'error',
                    'redirect' => 'article_index'
                ]
            ],
            defaultRedirect: 'article_index'
        );
    }

    #[Route('/{id}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(string $id): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($id)
            {
                $this->articleService->delete($id);
                return $this->successRedirect('Article supprimé avec succès !', 'article_index');
            },
            exceptionHandlers: [
                ArticleNotFoundException::class => [
                    'message' => 'Article non trouvé',
                    'type' => 'error',
                    'redirect' => 'article_index'
                ],
                ArticleDomainException::class => [
                    'message' => 'Erreur lors de la suppression',
                    'type' => 'error',
                    'redirect' => 'article_index'
                ]
            ],
            defaultRedirect: 'article_index'
        );
    }
}
