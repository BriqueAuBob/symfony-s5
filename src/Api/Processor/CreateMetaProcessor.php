<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Meta;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

final readonly class CreateMetaProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Slug $slugService,
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
        $content = $this->slugService->getEntityWithSlug($slug);

        if (null === $content) {
            throw new Exception('Content not found');
        }

        $meta = new Meta();
        $meta->name = $data->name;
        $meta->value = $data->value;
        $meta->content = $content;

        $this->em->persist($meta);
        $this->em->flush();

        return $meta;
    }
}
