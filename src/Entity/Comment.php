<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateCommentProcessor;
use App\Api\Processor\UpdateCommentProcessor;
use App\Api\Provider\CreateCommentProvider;
use App\Api\Serializer\CommentSerializer;
use App\Repository\CommentRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [new GetCollection()]
)]
#[ApiResource(
    uriTemplate: '/contents/{slug}/comments/{id}',
    operations: [
        new Get(),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object.author === user',
        ),
        new Put(
            security: 'object.author === user',
            processor: UpdateCommentProcessor::class
        ),
    ],
    uriVariables: [
        'slug' => new Link(
            fromProperty: 'comments',
            toProperty: 'content',
            fromClass: Content::class,
            description: 'The slug of the content',
        ),
        'id',
    ],
    normalizationContext: [CommentSerializer::class],
)]
#[ApiResource(
    uriTemplate: '/contents/{slug}/comments',
    operations: [
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_USER")',
            provider: CreateCommentProvider::class
        ),
    ],
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
class Comment
{
    use EntityTimestamps;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['comment:read'])]
    #[ApiProperty(writable: false)]
    public User|UserInterface|null $author = null;

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
