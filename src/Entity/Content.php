<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;


#[ORM\Entity]
class Content
{
    use EntityTimestamps, Uuid;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $title;

    #[ORM\Column(type: 'text')]
    public ?string $content;

    #[ORM\Column(type: 'text')]
    public ?string $slug;

    // thumbnail, tags with relationships, author with relationships, meta tags with relationships
    #[ORM\Column(type: 'text')]
    public ?string $thumbnail;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    public Collection $tags;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $author = null;

    #[ORM\OneToMany(targetEntity: Meta::class, mappedBy: 'content')]
    private Collection $meta;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'content', orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->meta = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
