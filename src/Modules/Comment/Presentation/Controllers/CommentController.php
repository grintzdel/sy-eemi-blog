<?php

declare(strict_types=1);

namespace App\Modules\Comment\Presentation\Controllers;

use App\Modules\Article\Application\Services\ArticleService;
use App\Modules\Article\Domain\Exceptions\ArticleNotFoundException;
use App\Modules\Article\Domain\ValueObjects\ArticleId;
use App\Modules\Comment\Application\Commands\CreateCommentCommand;
use App\Modules\Comment\Application\Commands\UpdateCommentCommand;
use App\Modules\Comment\Application\Services\CommentService;
use App\Modules\Comment\Domain\Exceptions\CommentNotFoundException;
use App\Modules\Comment\Domain\ValueObjects\CommentId;
use App\Modules\Comment\Presentation\Forms\CommentFormType;
use App\Modules\Comment\Presentation\WriteModel\CommentModel;
use App\Modules\Shared\Presentation\Controllers\AppController;
use App\Modules\User\Domain\ValueObjects\UserId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/comments')]
final class CommentController extends AppController
{
    public function __construct(
        private readonly CommentService $commentService,
        private readonly ArticleService $articleService,
    ) {}

    #[Route('/article/{articleId}/new', name: 'comment_new', methods: ['POST'])]
    public function new(Request $request, string $articleId): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($request, $articleId)
            {
                $form = $this->createForm(CommentFormType::class, new CommentModel());
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid())
                {
                    $model = $form->getData();

                    $this->articleService->findById($articleId);

                    $command = new CreateCommentCommand(
                        id: CommentId::fromString(Uuid::v4()->toString()),
                        content: $model->content,
                        articleId: ArticleId::fromString($articleId),
                        authorId: UserId::fromString($this->getUser()->getId()),
                    );

                    $this->commentService->create($command);

                    return $this->successRedirect('Comment added successfully!', 'article_show', ['id' => $articleId]);
                }

                return $this->redirectToRoute('article_show', ['id' => $articleId]);
            },
            exceptionHandlers: [
                ArticleNotFoundException::class => [
                    'message' => 'Article not found',
                    'type' => 'error',
                    'redirect' => 'article_index'
                ]
            ],
            defaultRedirect: 'article_show'
        );
    }

    #[Route('/{id}/edit', name: 'comment_edit', methods: ['POST'])]
    public function edit(Request $request, string $id): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($request, $id)
            {
                $comment = $this->commentService->findById($id);

                $this->denyAccessUnlessGranted('EDIT', $comment);

                $form = $this->createForm(CommentFormType::class, new CommentModel());
                $form->handleRequest($request);

                if($form->isSubmitted())
                {
                    if($form->isValid())
                    {
                        $model = $form->getData();

                        $command = new UpdateCommentCommand(
                            id: CommentId::fromString($id),
                            content: $model->content,
                        );

                        $this->commentService->update($command);

                        $articleId = $comment->getArticleId()->getValue();

                        return $this->successRedirect('Commentaire modifié avec succès !', 'article_show', ['id' => $articleId]);
                    } else
                    {
                        $articleId = $comment->getArticleId()->getValue();
                        $this->addFlash('error', 'Erreur de validation du formulaire');
                        return $this->redirectToRoute('article_show', ['id' => $articleId]);
                    }
                }

                $articleId = $comment->getArticleId()->getValue();
                $this->addFlash('error', 'Formulaire non soumis');
                return $this->redirectToRoute('article_show', ['id' => $articleId]);
            },
            exceptionHandlers: [
                CommentNotFoundException::class => [
                    'message' => 'Comment not found',
                    'type' => 'error',
                    'redirect' => 'article_index'
                ]
            ],
            defaultRedirect: 'article_index'
        );
    }

    #[Route('/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    public function delete(string $id): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($id)
            {
                $comment = $this->commentService->findById($id);

                $this->denyAccessUnlessGranted('DELETE', $comment);

                $articleId = $comment->getArticleId()->getValue();

                $this->commentService->delete($id);

                return $this->successRedirect('Comment deleted successfully!', 'article_show', ['id' => $articleId]);
            },
            exceptionHandlers: [
                CommentNotFoundException::class => [
                    'message' => 'Comment not found',
                    'type' => 'error',
                    'redirect' => 'article_index'
                ]
            ],
            defaultRedirect: 'article_index'
        );
    }
}
