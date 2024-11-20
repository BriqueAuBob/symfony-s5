<?php declare(strict_types=1);

namespace App\ApiProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Routing\IriConverter;
use ApiPlatform\Validator\ValidatorInterface;
use App\ApiResource\CreateContent;
use App\Entity\Content;
use App\Entity\User;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final readonly class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private Slug $slugService,
        private Security $security,
    ) {
    }

    /** @param CreateContent $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {
        $data->slug = $this->slugService->get($data->title);
        $data->author = $this->security->getUser();

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}
