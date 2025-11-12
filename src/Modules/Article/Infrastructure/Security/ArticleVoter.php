<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Security;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ArticleVoter extends Voter
{
    public const string EDIT = 'EDIT';
    public const string DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof ArticleEntity;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if(!$user instanceof DoctrineUserEntity)
        {
            return false;
        }

        $article = $subject;

        $isAuthor = (string)$article->getAuthorUsername() === $user->getUsername();

        $isAdmin = $user->getRole() === Roles::ROLE_ADMIN;

        return match ($attribute)
        {
            self::EDIT, self::DELETE => $isAuthor || $isAdmin,
            default => false,
        };
    }
}
