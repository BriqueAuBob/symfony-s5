<?php declare(strict_types=1);

namespace App\Trait;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;

trait UuidUnidentifier
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(identifier: false)]
    public ?\Symfony\Component\Uid\Uuid $id = null;

    public function getId(): ?\Symfony\Component\Uid\Uuid
    {
        return $this->id;
    }
}
