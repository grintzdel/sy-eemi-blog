<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Presentation\WriteModel;

use App\Modules\Authentication\Presentation\Validators\MinimumAge;
use Symfony\Component\Validator\Constraints as Assert;

final class RegistrationModel
{
    #[Assert\NotBlank(message: 'Username is required')]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'Username must be at least {{ limit }} characters long',
        maxMessage: 'Username cannot be longer than {{ limit }} characters'
    )]
    public string $username = '';

    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Birthdate is required')]
    #[MinimumAge]
    public ?\DateTimeImmutable $birthdate = null;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Password must be at least {{ limit }} characters long'
    )]
    public string $plainPassword = '';
}
