<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\WriteModel;

use Symfony\Component\Validator\Constraints as Assert;

final class UserModel
{
    public function __construct(
        #[Assert\NotBlank(message: 'Username is required')]
        #[Assert\Length(
            min: 3,
            max: 20,
            minMessage: 'Username must be at least {{ limit }} characters long',
            maxMessage: 'Username cannot be longer than {{ limit }} characters'
        )]
        public ?string             $username = null,

        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'The email "{{ value }}" is not a valid email')]
        public ?string             $email = null,

        #[Assert\NotBlank(message: 'Birthdate is required')]
        #[Assert\Type(\DateTimeInterface::class, message: 'Birthdate must be a valid date')]
        #[Assert\LessThan('today', message: 'Birthdate must be in the past')]
        public ?\DateTimeImmutable $birthdate = null,
    ) {}

}
