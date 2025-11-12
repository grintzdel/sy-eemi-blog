<?php

declare(strict_types=1);

namespace App\Modules\Comment\Infrastructure\Security;

use App\Modules\Comment\Domain\Entities\CommentEntity;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CommentVoter extends Voter
{
    public const string EDIT = 'EDIT';
    public const string DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof CommentEntity;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if(!$user instanceof DoctrineUserEntity)
        {
            return false;
        }

        $comment = $subject;

        $isAuthor = $comment->getAuthorId()->getValue() === $user->getId();

        $isModerator = $user->getRole() === Roles::ROLE_MODERATOR;

        $isAdmin = $user->getRole() === Roles::ROLE_ADMIN;

        return match ($attribute)
        {
            self::EDIT, self::DELETE => $isAuthor || $isAdmin || $isModerator,
            default => false,
        };
    }
}
