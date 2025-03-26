<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateContent;
use App\Entity\Content;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
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
