<?php

namespace App\Entity;

use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_TAG_NAME', columns: ['name'])]
#[UniqueEntity(fields: ['name'])]
class Tag
{
    use EntityTimestamps, Uuid;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    public ?string $name;
}
