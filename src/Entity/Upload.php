<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Api\Action\UploadAction;
use App\Trait\EntityTimestamps;
use App\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'uploads')]
#[Get]
#[Post(controller: UploadAction::class, deserialize: false)]
#[ORM\HasLifecycleCallbacks]
class Upload
{
    use Uuid;
    use EntityTimestamps;

    #[ORM\Column]
    #[Groups(['content:read'])]
    public ?string $path = null;
}
