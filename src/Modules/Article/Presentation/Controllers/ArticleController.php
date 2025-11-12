<?php

declare(strict_types=1);

namespace App\Modules\Article\Presentation\Controllers;

use App\Modules\Article\Application\Commands\CreateArticleCommand;
use App\Modules\Article\Application\Commands\UpdateArticleCommand;
use App\Modules\Article\Application\Services\ArticleService;
use App\Modules\Article\Domain\Exceptions\ArticleDomainException;
use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Article\Presentation\Forms\ArticleFormType;
use App\Modules\Article\Presentation\WriteModel\ArticleModel;
use App\Modules\Shared\Presentation\Controllers\AppController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

#[Route('/articles')]
final class ArticleController extends AppController
{
    public function __construct(
        private readonly ArticleService   $articleService,
        private readonly SluggerInterface $slugger,
        private readonly string           $uploadDirectory = 'uploads/articles'
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
            form: $this->createForm(ArticleFormType::class, new ArticleModel()),
            onSuccess: function(ArticleModel $model)
            {
                $coverImageFilename = $this->handleFileUpload($model->coverImage);

                $command = new CreateArticleCommand(
                    id: ArticleId::fromString(Uuid::v4()->toString()),
                    heading: $model->heading,
                    subheading: $model->subheading,
                    content: $model->content,
                    authorUsername: $this->getUser()->getUsername(),
                    coverImage: $coverImageFilename,
                );

                $this->articleService->create($command);

                return $this->successRedirect('Article created successfully!', 'article_index');
            },
            template: 'article/new.html.twig',
            exceptionHandlers: [
                ArticleDomainException::class => [
                    'message' => 'Error creating article',
                    'type' => 'error'
                ]
            ]
        );
    }

    private function handleFileUpload(?object $file): ?string
    {
        if(!$file)
        {
            return null;
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try
        {
            $file->move(
                $this->getParameter('kernel.project_dir') . '/public/' . $this->uploadDirectory,
                $newFilename
            );
        } catch(FileException $e)
        {
            return null;
        }

        return $newFilename;
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($id, $request)
            {
                $article = $this->articleService->findById($id);
                $model = ArticleModel::createFromEntity($article);

                return $this->handleForm(
                    request: $request,
                    form: $this->createForm(ArticleFormType::class, $model),
                    onSuccess: function(ArticleModel $model) use ($id, $article)
                    {
                        $coverImageFilename = $this->handleFileUpload($model->coverImage);

                        $command = new UpdateArticleCommand(
                            id: ArticleId::fromString($id),
                            heading: $model->heading,
                            subheading: $model->subheading,
                            content: $model->content,
                            authorUsername: null, // L'auteur ne change pas lors de la mise à jour
                            coverImage: $coverImageFilename ?? $article->getCoverImage(),
                        );

                        $this->articleService->update($command);

                        return $this->successRedirect('Article updated successfully!', 'article_index');
                    },
                    template: 'article/edit.html.twig',
                    templateData: ['article' => $article],
                    exceptionHandlers: [
                        ArticleDomainException::class => [
                            'message' => 'Error updating article',
                            'type' => 'error'
                        ]
                    ]
                );
            },
            exceptionHandlers: [
                ArticleNotFoundException::class => [
                    'message' => 'Article not found',
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
