<?php
declare(strict_types=1);

namespace App\Trait;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;

trait EntityTimestamps
{
    #[ORM\Column(type: 'datetime')]
    #[ApiProperty(readable: true, writable: false)]
    public ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    #[ApiProperty(readable: true, writable: false)]
    public ?\DateTime $updatedAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
