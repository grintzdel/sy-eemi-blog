<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Presentation\Controllers;

use App\Modules\Authentication\Application\Commands\RegisterUserCommand;
use App\Modules\Authentication\Application\Services\AuthenticationService;
use App\Modules\Authentication\Presentation\Forms\RegistrationFormType;
use App\Modules\Authentication\Presentation\WriteModel\RegistrationModel;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\Shared\Presentation\Controllers\AppController;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\Username;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AppController
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
    ) {}

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $registrationModel = new RegistrationModel();
        $form = $this->createForm(RegistrationFormType::class, $registrationModel);

        return $this->handleForm(
            $request,
            $form,
            function(RegistrationModel $data): Response
            {
                $command = new RegisterUserCommand(
                    UserId::generate(),
                    new Username($data->username),
                    new Email($data->email),
                    $data->birthdate,
                    $data->plainPassword,
                    Roles::USER
                );

                $this->authenticationService->registerUser($command);

                return $this->successRedirect(
                    'Registration successful! You can now log in.',
                    'app_login'
                );
            },
            'authentication/register.html.twig',
        );
    }
}
