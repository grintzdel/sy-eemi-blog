<?php

declare(strict_types=1);

namespace App\Modules\Shared\Infrastructure\DataFixtures;

use App\Modules\Article\Infrastructure\Doctrine\Entities\DoctrineArticleEntity;
use App\Modules\User\Infrastructure\Doctrine\Entities\DoctrineUserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class ArticleFixture extends Fixture implements DependentFixtureInterface
{
    private const array ARTICLES_DATA = [
        [
            'heading' => 'Getting Started with Symfony 7',
            'subheading' => 'A comprehensive guide to modern PHP development',
            'content' => 'Symfony 7 introduces powerful features that make PHP development more enjoyable and productive. From improved performance to better developer experience, this new version brings significant improvements to the framework. In this article, we will explore the key features and how to get started with your first Symfony 7 project.'
        ],
        [
            'heading' => 'Mastering Domain-Driven Design',
            'subheading' => 'Build maintainable applications with DDD principles',
            'content' => 'Domain-Driven Design is a software development approach that focuses on modeling your software based on the business domain. By using concepts like entities, value objects, and aggregates, you can create applications that are easier to understand and maintain. This article will guide you through the essential DDD patterns and how to apply them in your projects.'
        ],
        [
            'heading' => 'Understanding Hexagonal Architecture',
            'subheading' => 'Separate your business logic from infrastructure concerns',
            'content' => 'Hexagonal Architecture, also known as Ports and Adapters, is an architectural pattern that promotes separation of concerns. By isolating your domain logic from external dependencies like databases and APIs, you create more testable and maintainable code. Learn how to structure your application using this powerful pattern.'
        ],
        [
            'heading' => 'Best Practices for PHP Development',
            'subheading' => 'Write clean, maintainable, and efficient code',
            'content' => 'Writing quality PHP code requires following best practices and conventions. From proper naming conventions to SOLID principles, these guidelines will help you create professional applications. This article covers essential practices every PHP developer should know, including dependency injection, interface segregation, and more.'
        ],
        [
            'heading' => 'Introduction to Doctrine ORM',
            'subheading' => 'Simplify database interactions in PHP',
            'content' => 'Doctrine ORM is a powerful object-relational mapping library for PHP. It allows you to work with databases using PHP objects instead of writing SQL queries. Learn about entities, repositories, migrations, and how to leverage Doctrine to build robust data access layers in your applications.'
        ],
        [
            'heading' => 'Building RESTful APIs with Symfony',
            'subheading' => 'Create scalable and maintainable web services',
            'content' => 'REST APIs are essential for modern web applications. Symfony provides excellent tools for building robust APIs with proper routing, serialization, and validation. This guide covers everything from basic endpoint creation to advanced topics like API versioning and authentication.'
        ],
        [
            'heading' => 'Test-Driven Development in PHP',
            'subheading' => 'Write better code through automated testing',
            'content' => 'Test-Driven Development is a software development practice where you write tests before writing the actual code. This approach leads to better design, fewer bugs, and more confidence in your codebase. Learn how to implement TDD in your PHP projects using PHPUnit and Symfony testing tools.'
        ],
        [
            'heading' => 'Security Best Practices for Web Applications',
            'subheading' => 'Protect your application from common vulnerabilities',
            'content' => 'Security is crucial for any web application. From SQL injection to XSS attacks, understanding common vulnerabilities is the first step to protecting your users. This article covers essential security practices including input validation, CSRF protection, authentication, and authorization strategies.'
        ],
        [
            'heading' => 'Optimizing Database Performance',
            'subheading' => 'Make your queries faster and more efficient',
            'content' => 'Database performance can make or break your application. Learn how to optimize your queries, use indexes effectively, and avoid common performance pitfalls. This guide covers query optimization, database design patterns, caching strategies, and profiling tools to identify bottlenecks.'
        ],
        [
            'heading' => 'Modern Frontend Development with Symfony',
            'subheading' => 'Integrate modern JavaScript frameworks with Symfony',
            'content' => 'Modern web applications require sophisticated frontend technologies. Symfony provides excellent integration with tools like Webpack Encore, allowing you to use modern JavaScript frameworks while maintaining a clean architecture. Learn how to set up and optimize your frontend development workflow.'
        ]
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

        foreach(self::ARTICLES_DATA as $index => $articleData)
        {
            /** @var DoctrineUserEntity $author */
            $author = $users[array_rand($users)];

            $daysAgo = 30 - ($index * 3);
            $createdAt = new \DateTimeImmutable("-{$daysAgo} days");

            $article = new DoctrineArticleEntity(
                id: Uuid::v4()->toString(),
                heading: $articleData['heading'],
                subheading: $articleData['subheading'],
                content: $articleData['content'],
                author: $author,
                coverImage: null,
                createdAt: $createdAt,
                updatedAt: $createdAt
            );

            $manager->persist($article);
            $this->setReference('article_' . $index, $article);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }

    public function getOrder(): int
    {
        return 2;
    }
}
