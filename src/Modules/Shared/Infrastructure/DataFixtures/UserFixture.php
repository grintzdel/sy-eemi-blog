<?php

declare(strict_types=1);

namespace App\Modules\Shared\Infrastructure\DataFixtures;

use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final class UserFixture extends Fixture
{
    public const string ADMIN_REFERENCE = 'user_admin';
    public const string MODERATOR_REFERENCE = 'user_moderator';
    public const string USER_1_REFERENCE = 'user_1';
    public const string USER_2_REFERENCE = 'user_2';
    public const string USER_3_REFERENCE = 'user_3';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new DoctrineUserEntity(
            id: Uuid::v4()->toString(),
            username: 'admin',
            email: 'admin@example.com',
            age: 35,
            password: '',
            role: Roles::ROLE_ADMIN
        );
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);
        $this->setReference(self::ADMIN_REFERENCE, $admin);

        $moderator = new DoctrineUserEntity(
            id: Uuid::v4()->toString(),
            username: 'moderator',
            email: 'moderator@example.com',
            age: 28,
            password: '',
            role: Roles::ROLE_MODERATOR
        );
        $hashedPassword = $this->passwordHasher->hashPassword($moderator, 'moderator123');
        $moderator->setPassword($hashedPassword);
        $manager->persist($moderator);
        $this->setReference(self::MODERATOR_REFERENCE, $moderator);

        $user1 = new DoctrineUserEntity(
            id: Uuid::v4()->toString(),
            username: 'johndoe',
            email: 'john.doe@example.com',
            age: 25,
            password: '',
            role: Roles::ROLE_USER
        );
        $hashedPassword = $this->passwordHasher->hashPassword($user1, 'user123');
        $user1->setPassword($hashedPassword);
        $manager->persist($user1);
        $this->setReference(self::USER_1_REFERENCE, $user1);

        $user2 = new DoctrineUserEntity(
            id: Uuid::v4()->toString(),
            username: 'janedoe',
            email: 'jane.doe@example.com',
            age: 30,
            password: '',
            role: Roles::ROLE_USER
        );
        $hashedPassword = $this->passwordHasher->hashPassword($user2, 'user123');
        $user2->setPassword($hashedPassword);
        $manager->persist($user2);
        $this->setReference(self::USER_2_REFERENCE, $user2);

        $user3 = new DoctrineUserEntity(
            id: Uuid::v4()->toString(),
            username: 'bobsmith',
            email: 'bob.smith@example.com',
            age: 42,
            password: '',
            role: Roles::ROLE_USER
        );
        $hashedPassword = $this->passwordHasher->hashPassword($user3, 'user123');
        $user3->setPassword($hashedPassword);
        $manager->persist($user3);
        $this->setReference(self::USER_3_REFERENCE, $user3);

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}
