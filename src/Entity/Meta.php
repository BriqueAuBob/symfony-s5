<?php

namespace App\Entity;

use App\Repository\MetaRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: MetaRepository::class)]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
class Meta
{
    use EntityTimestamps, Uuid;

    #[ORM\Column(length: 40)]
    public ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'meta')]
    public ?Content $content = null;
}
