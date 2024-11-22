<?php declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\State\ProviderInterface;

final class CreateCommentProvider implements ProviderInterface
{
    public function __construct(
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        if ('/comments' !== $operation->getUriTemplate()) {
            return null;
        }

        $resourceMetadata = $this->resourceMetadataCollectionFactory->create($operation->getMetadata()->getResourceClass());
        $resource = $resourceMetadata->getOperation('post');
        $resource->setUriTemplate('/comments/{id}');

        return $resource;
    }
}
