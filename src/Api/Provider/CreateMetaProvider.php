<?php declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Metadata\Exception\RuntimeException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\State\ProviderInterface;

final class CreateMetaProvider implements ProviderInterface
{
    public function __construct(
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        if ('/metas' !== $operation->getUriTemplate()) {
            return null;
        }

        $resourceMetadata = $this->resourceMetadataCollectionFactory->create($operation->getMetadata()->getResourceClass());
        $resource = $resourceMetadata->getOperation('post');

        if (null === $resource) {
            throw new RuntimeException('Resource not found');
        }

        $resource->setUriTemplate('/metas/{id}');

        return $resource;
    }
}
