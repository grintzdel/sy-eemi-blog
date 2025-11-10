<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Controllers;

use App\Modules\Shared\Presentation\Controllers\AppController;
use App\Modules\User\Application\Commands\CreateUserCommand;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Domain\Exceptions\UserDomainException;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\Username;
use App\Modules\User\Presentation\Forms\UserFormType;
use App\Modules\User\Presentation\WriteModel\UserModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/users')]
final class UserController extends AppController
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(string $id): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($id)
            {
                $user = $this->userService->findById($id);

                return $this->render('user/show.html.twig', [
                    'user' => $user,
                ]);
            },
            exceptionHandlers: [
                UserNotFoundException::class => [
                    'message' => 'User not found',
                    'type' => 'error',
                    'redirect' => 'user_index'
                ]
            ],
            defaultRedirect: 'user_index'
        );
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return $this->handleForm(
            request: $request,
            form: $this->createForm(UserFormType::class, new UserModel()),
            onSuccess: function(UserModel $model)
            {
                $command = new CreateUserCommand(
                    id: UserId::fromString(Uuid::v4()->toString()),
                    username: new Username($model->username),
                    email: new Email($model->email),
                    birthdate: $model->birthdate,
                );

                $this->userService->create($command);

                return $this->successRedirect('User created successfully!', 'user_index');
            },
            template: 'user/new.html.twig',
            exceptionHandlers: [
                UserDomainException::class => [
                    'message' => 'Error creating user',
                    'type' => 'error'
                ]
            ]
        );
    }

    #[Route('/email/{email}', name: 'user_find_by_email', methods: ['GET'])]
    public function findByEmail(string $email): Response
    {
        return $this->executeWithExceptionHandling(
            operation: function() use ($email)
            {
                $user = $this->userService->findByEmail($email);

                if($user === null)
                {
                    $this->addFlash('warning', 'No user found with this email');
                    return $this->redirectToRoute('user_index');
                }

                return $this->render('user/show.html.twig', [
                    'user' => $user,
                ]);
            },
            exceptionHandlers: [
                UserDomainException::class => [
                    'message' => 'Error searching for user',
                    'type' => 'error',
                    'redirect' => 'user_index'
                ]
            ],
            defaultRedirect: 'user_index'
        );
    }
}
