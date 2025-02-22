<?php declare(strict_types=1);

namespace App\Trait;

use ApiPlatform\Metadata\ApiProperty;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait EntityTimestamps
{
    #[ORM\Column(type: 'datetime')]
    #[ApiProperty(readable: true, writable: false)]
    #[Groups(['content:read'])]
    public ?DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    #[ApiProperty(readable: true, writable: false)]
    #[Groups(['content:read'])]
    public ?DateTime $updatedAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }
}
