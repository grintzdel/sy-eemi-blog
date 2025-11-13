<?php

declare(strict_types=1);

namespace App\Modules\Shared\Infrastructure\DataFixtures;

use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use App\Modules\Comment\Infrastructure\Doctrine\Entities\DoctrineCommentEntity;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class CommentFixture extends Fixture implements DependentFixtureInterface
{
    private const array COMMENT_TEMPLATES = [
        'Great article! This really helped me understand %s better.',
        'Thanks for sharing this. Very informative and well written.',
        'I have been looking for information on %s and this article is perfect.',
        'Excellent explanation! Could you elaborate more on the practical applications?',
        'This is exactly what I needed. Looking forward to more content like this.',
        'Very clear and concise. I appreciate the detailed examples.',
        'Interesting perspective on %s. Have you considered the alternative approach?',
        'Bookmarked for future reference. This will be very useful.',
        'Well structured article. The examples really help clarify the concepts.',
        'Thanks for breaking this down in an easy-to-understand way.',
        'I implemented this in my project and it works perfectly!',
        'This article answered all my questions about %s.',
        'Clear, concise, and practical. Exactly what I was looking for.',
        'The code examples are really helpful. Thanks for sharing!',
        'This is a fantastic resource. I will definitely share it with my team.',
        'Great insights! I never thought about it from that angle.',
        'Very practical advice. I will try implementing this approach.',
        'This cleared up a lot of confusion I had about %s.',
        'Excellent work! Looking forward to reading more of your articles.',
        'Simple yet effective explanation. Thank you!'
    ];

    public function load(ObjectManager $manager): void
    {
        $users = [
            $this->getReference(UserFixture::ADMIN_REFERENCE, DoctrineUserEntity::class),
            $this->getReference(UserFixture::MODERATOR_REFERENCE, DoctrineUserEntity::class),
            $this->getReference(UserFixture::USER_1_REFERENCE, DoctrineUserEntity::class),
            $this->getReference(UserFixture::USER_2_REFERENCE, DoctrineUserEntity::class),
            $this->getReference(UserFixture::USER_3_REFERENCE, DoctrineUserEntity::class),
        ];

        for($articleIndex = 0; $articleIndex < 10; $articleIndex++)
        {
            /** @var DoctrineArticleEntity $article */
            $article = $this->getReference('article_' . $articleIndex, DoctrineArticleEntity::class);

            $numberOfComments = rand(2, 5);

            for($i = 0; $i < $numberOfComments; $i++)
            {
                /** @var DoctrineUserEntity $author */
                $author = $users[array_rand($users)];

                $articleCreatedAt = $article->getCreatedAt();
                $hoursAfterArticle = rand(1, 48) + ($i * 2);
                $createdAt = $articleCreatedAt->modify("+{$hoursAfterArticle} hours");

                $template = self::COMMENT_TEMPLATES[array_rand(self::COMMENT_TEMPLATES)];
                $content = str_contains($template, '%s')
                    ? sprintf($template, $this->extractKeyword($article->getHeading()))
                    : $template;

                $comment = new DoctrineCommentEntity(
                    id: Uuid::v4()->toString(),
                    content: $content,
                    article: $article,
                    author: $author,
                    createdAt: $createdAt,
                    updatedAt: $createdAt
                );

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    private function extractKeyword(string $heading): string
    {
        $keywords = [
            'Symfony' => 'Symfony',
            'Domain-Driven' => 'DDD',
            'Hexagonal' => 'this architecture',
            'PHP' => 'PHP',
            'Doctrine' => 'Doctrine ORM',
            'RESTful' => 'REST APIs',
            'Test-Driven' => 'TDD',
            'Security' => 'security',
            'Database' => 'database optimization',
            'Frontend' => 'frontend development'
        ];

        foreach($keywords as $search => $replacement)
        {
            if(stripos($heading, $search) !== false)
            {
                return $replacement;
            }
        }

        return 'this topic';
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            ArticleFixture::class,
        ];
    }

    public function getOrder(): int
    {
        return 3;
    }
}
