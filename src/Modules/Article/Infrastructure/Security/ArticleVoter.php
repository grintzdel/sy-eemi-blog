<?php

declare(strict_types=1);

namespace App\Modules\Article\Infrastructure\Security;

use App\Modules\Article\Domain\Entities\ArticleEntity;
use App\Modules\Article\Presentation\ViewModels\ArticleViewModel;
use App\Modules\Shared\Domain\Enums\Roles;
use App\Modules\Shared\Domain\Enums\VoterActions;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ArticleVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [VoterActions::EDIT->value, VoterActions::DELETE->value], true)
            && ($subject instanceof ArticleEntity || $subject instanceof ArticleViewModel);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if(!$user instanceof DoctrineUserEntity)
        {
            return false;
        }


        $isAuthor = false;
        if($subject instanceof ArticleViewModel)
        {
            $isAuthor = $subject->authorUsername === $user->getUsername();
        } elseif($subject instanceof ArticleEntity)
        {
            $isAuthor = $subject->getAuthorId()->getValue() === $user->getId();
        }

        $isAdmin = $user->getRole() === Roles::ROLE_ADMIN;

        return match ($attribute)
        {
            VoterActions::EDIT->value, VoterActions::DELETE->value => $isAuthor || $isAdmin,
            default => false,
        };
    }
}
