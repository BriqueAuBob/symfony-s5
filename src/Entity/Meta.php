<?php

namespace App\Entity;

use App\Repository\MetaRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MetaRepository::class)]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
class Meta
{
    use EntityTimestamps, Uuid;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 40)]
    public ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    public ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'meta')]
    public ?Content $content = null;
}
