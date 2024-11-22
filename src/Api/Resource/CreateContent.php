<?php declare(strict_types=1);

namespace App\Api\Resource;

use App\Entity\Meta;
use App\Entity\Upload;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateContent
{
    public ?string $title = null;
    public ?string $content = null;
    public ?Upload $thumbnail = null;
    public ?Collection $tags = null;
    /**
     * @var Meta[]|null
     */
    public ?array $meta = [];
    public User|UserInterface|null $author = null;
    public ?string $slug = null;
}
