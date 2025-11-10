<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Doctrine\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'doctrine_user_entity')]
final readonly class DoctrineUserEntity
{
    public function __construct() {}
}
