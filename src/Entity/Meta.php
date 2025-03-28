<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateMetaProcessor;
use App\Api\Provider\CreateMetaProvider;
use App\Repository\MetaRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MetaRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    uriTemplate: '/contents/{slug}/metas/{id}',
    operations: [
        new Get(),
        new Put(
            security: 'is_granted("ROLE_ADMIN") && object.content.author === user',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") && object.content.author === user',
        ),
    ],
    uriVariables: [
        'slug' => new Link(
            fromProperty: 'metas',
            toProperty: 'content',
            fromClass: Content::class,
            description: 'The slug of the content',
        ),
        'id',
    ],
)]
#[ApiResource(
    uriTemplate: '/contents/{slug}/metas',
    operations: [
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
            provider: CreateMetaProvider::class,
            processor: CreateMetaProcessor::class
        )
    ],
    uriVariables: [
        'slug' => new Link(
            fromProperty: 'metas',
            toProperty: 'content',
            fromClass: Content::class,
            description: 'The slug of the content',
        ),
    ],
)]
class Meta
{
    use EntityTimestamps;
    use Uuid;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 40)]
    #[Groups(['meta:read'])]
    public ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['meta:read'])]
    public ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'metas')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public ?Content $content = null;
}
