<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Doctrine\Entities;

use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Modules\User\Domain\ValueObjects\Age;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\Password;
use App\Modules\User\Domain\ValueObjects\UserId;
use App\Modules\User\Domain\ValueObjects\Username;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
final class DoctrineUserEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'integer')]
    private int $age;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 20, enumType: Roles::class)]
    private Roles $role;

    public function __construct(
        string $id = '',
        string $username = '',
        string $email = '',
        int    $age = 0,
        string $password = '',
        Roles  $role = Roles::ROLE_USER
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->age = $age;
        $this->password = $password;
        $this->role = $role;
    }

    public static function fromDomain(UserEntity $user): self
    {
        return new self(
            $user->getId()->getValue(),
            $user->getUsername()->value,
            $user->getEmail()->value,
            $user->getAge()->value,
            $user->getPassword()->value,
            $user->getRole()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): Roles
    {
        return $this->role;
    }

    public function setRole(Roles $role): void
    {
        $this->role = $role;
    }

    public function setPasswordForVerification(string $password): void
    {
        $this->password = $password;
    }

    public function toDomain(): UserEntity
    {
        return new UserEntity(
            UserId::fromString($this->id),
            new Username($this->username),
            new Email($this->email),
            Age::from($this->age),
            Password::fromHashed($this->password),
            $this->role
        );
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
