<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Meta;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CreateMetaProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private Slug $slugService,
        private Security $security,
    ) {
    }

    /** @param Meta $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Meta {
        $slug = $uriVariables['slug'];

        $meta = new Meta();
        $meta->name = $data->name;
        $meta->value = $data->value;
        $meta->content = $this->slugService->getEntityWithSlug($slug);

        $this->em->persist($meta);
        $this->em->flush();

        return $meta;
    }
}
