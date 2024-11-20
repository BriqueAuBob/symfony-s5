<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiProcessor\CreateContentProcessor;
use App\ApiProcessor\UpdateContentProcessor;
use App\ApiResource\CreateContent;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
#[GetCollection]
#[Get(uriVariables: 'slug')]
#[Delete(uriVariables: 'slug')]
#[Post(uriVariables: 'slug', security: 'is_granted("ROLE_ADMIN")', processor: CreateContentProcessor::class)]
#[Put(
    uriVariables: 'slug',
    security: 'is_granted("ROLE_ADMIN") and object.author == user',
    processor: UpdateContentProcessor::class)
]
#[ORM\UniqueConstraint(name: 'UNIQ_SLUG', fields: ['slug'])]
#[ORM\HasLifecycleCallbacks]
class Content
{
    use EntityTimestamps, Uuid;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    public ?string $content;

    #[ORM\Column(type: 'text')]
    public ?string $slug = null;

    // thumbnail, tags with relationships, author with relationships, meta tags with relationships
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    public ?string $thumbnail = null;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ApiProperty]
    public ?Collection $tags = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    public ?User $author = null;

    #[ORM\OneToMany(targetEntity: Meta::class, mappedBy: 'content')]
    private ?Collection $meta = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'content', orphanRemoval: true)]
    private ?Collection $comments = null;

    public function __construct()
    {
        $this->meta = new ArrayCollection();
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
        if (!$this->meta->contains($meta)) {
            $this->meta->add($meta);
            $meta->setContent($this);
        }

        return $this;
    }

    public function removeMeta(Meta $metum): static
    {
        if ($this->meta->removeElement($metum)) {
            // set the owning side to null (unless already changed)
            if ($metum->getContent() === $this) {
                $metum->setContent(null);
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
            $comment->setContent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getContent() === $this) {
                $comment->setContent(null);
            }
        }

        return $this;
    }
}
