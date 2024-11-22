<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Repository\UserRepository;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use App\Validator\UnregisteredEmail;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
#[GetCollection(security: 'is_granted("ROLE_ADMIN")')]
#[Get(security: 'is_granted("ROLE_ADMIN") or object === user')]
#[Post(input: CreateUser::class, processor: CreateUserProcessor::class)]
#[ApiFilter(SearchFilter::class, properties: ['email' => ''])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EntityTimestamps;
    use Uuid;

    #[ORM\Column(length: 180)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 180)]
    #[Assert\Email]
    #[UnregisteredEmail]
    #[Groups(['content:read'])]
    public ?string $email = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    public array $roles = [];

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Ignore]
    public ?string $password = null;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
