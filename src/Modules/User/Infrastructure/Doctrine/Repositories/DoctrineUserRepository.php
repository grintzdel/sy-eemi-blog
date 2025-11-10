<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Doctrine\Repositories;

use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Repositories\IUserRepository;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineUserRepository implements IUserRepository
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(DoctrineUserEntity::class);
    }

    /*
     * Commands
     */
    public function create(UserEntity $user): UserEntity
    {
        $doctrineUser = DoctrineUserEntity::fromDomain($user);

        $this->entityManager->persist($doctrineUser);
        $this->entityManager->flush();

        return $doctrineUser->toDomain();
    }

    /*
     * Queries
     */
    public function findById(UserId $id): UserEntity
    {
        $doctrineUser = $this->repository->find($id->getValue());

        if($doctrineUser === null)
        {
            throw UserNotFoundException::withId($id->getValue());
        }

        return $doctrineUser->toDomain();
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        $doctrineUser = $this->repository->findOneBy(['email' => $email->value]);

        return $doctrineUser?->toDomain();

    }

}
