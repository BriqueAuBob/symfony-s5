<?php

namespace App\Entity;

use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
class Tag
{
    use EntityTimestamps, Uuid;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $name;
}
