<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateCommentProcessor;
use App\Api\Provider\CommentProvider;
use App\Api\Serializer\CommentSerializer;
use App\Repository\CommentRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
#[ApiResource(
    uriTemplate: '/contents/{slug}/comments/{id}',
    operations: [new Get(), new Delete()],
    uriVariables: [
        'slug' => new Link(
            fromProperty: 'comments',
            toProperty: 'content',
            fromClass: Content::class,
            description: 'The slug of the content',
        ),
        'id'
    ],
    normalizationContext: [CommentSerializer::class],
)]
#[ApiResource(
    uriTemplate: '/contents/{slug}/comments',
    operations: [new GetCollection(), new Post()],
    uriVariables: [
        'slug' => new Link(
            fromProperty: 'comments',
            toProperty: 'content',
            fromClass: Content::class,
            description: 'The slug of the content',
        ),
    ],
    normalizationContext: [CommentSerializer::class],
    processor: CreateCommentProcessor::class,
)]
#[ApiResource]
class Comment
{
    use EntityTimestamps, Uuid;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['comment:read'])]
    public ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    public ?Content $content = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['comment:read'])]
    public ?string $text = null;
}
