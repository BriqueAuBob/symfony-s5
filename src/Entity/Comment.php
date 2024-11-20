<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    use EntityTimestamps, Uuid;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Content $content = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $text = null;
}
