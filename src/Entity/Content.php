<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Filter\UuidFilter;
use App\Api\Processor\CreateContentProcessor;
use App\Api\Processor\UpdateContentProcessor;
use App\Trait\EntityTimestamps;
use App\Trait\UuidUnidentifier;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(normalizationContext: ['groups' => ['content:read', 'metas:read']])]
#[GetCollection]
#[Get(uriVariables: 'slug', normalizationContext: ['groups' => ['content:read', 'metas:read', 'content:all']])]
#[Delete(uriVariables: 'slug')]
#[Post(uriVariables: 'slug', security: 'is_granted("ROLE_ADMIN")', processor: CreateContentProcessor::class)]
#[Put(
    uriVariables: 'slug',
    security: 'is_granted("ROLE_ADMIN") and object.author == user',
    processor: UpdateContentProcessor::class
)]
#[ORM\UniqueConstraint(name: 'UNIQ_SLUG', fields: ['slug'])]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
#[ApiFilter(UuidFilter::class, properties: ['author' => 'exact'])]
class Content
{
    use EntityTimestamps;
    use UuidUnidentifier;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['content:read'])]
    public ?string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    #[Groups(['content:all'])]
    public ?string $content;

    #[ORM\Column(type: 'text')]
    #[ApiProperty(identifier: true)]
    #[Groups(['content:read', 'metas:read'])]
    public ?string $slug = null;

    #[ORM\OneToOne(targetEntity: Upload::class)]
    #[ApiProperty]
    #[Groups(['content:read'])]
    public ?Upload $thumbnail = null;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ApiProperty]
    #[Groups(['content:read'])]
    public ?Collection $tags = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    #[Groups(['content:read'])]
    public User|UserInterface|null $author = null;

    #[ORM\OneToMany(targetEntity: Meta::class, mappedBy: 'content', orphanRemoval: true)]
    #[Groups(['content:read'])]
    private ?Collection $metas = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'content', orphanRemoval: true)]
    #[Groups(['comment:read', 'content:read'])]
    #[Ignore]
    private ?Collection $comments = null;

    public function __construct()
    {
        $this->metas = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function addMeta(Meta $meta): static
    {
        if (!$this->metas->contains($meta)) {
            $this->metas->add($meta);
            $meta->content = $this;
        }

        return $this;
    }

    public function removeMeta(Meta $metum): static
    {
        if ($this->metas->removeElement($metum)) {
            // set the owning side to null (unless already changed)
            if ($metum->content === $this) {
                $metum->content = null;
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->content = $this;
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->content === $this) {
                $comment->content = null;
            }
        }

        return $this;
    }
}
