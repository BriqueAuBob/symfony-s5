<?php declare(strict_types=1);

namespace App\Api\Serializer;

use App\Entity\Comment;
use ArrayObject;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use UnexpectedValueException;
use function is_array;

class CommentSerializer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @param array<mixed> $context
     * @return array<mixed>|ArrayObject<mixed>
     */
    public function normalize($comment, ?string $format = null, array $context = []): array|ArrayObject
    {
        $data = $this->normalizer->normalize($comment, $format, $context);

        if (!is_array($data) && !$data instanceof ArrayObject) {
            throw new UnexpectedValueException('The normalizer did not return an array or ArrayObject.');
        }

        $data['author'] = $this->normalizer->normalize($comment->author);
        $data['content_id'] = $comment->content->id;
        $data['content_slug'] = $comment->content->slug;

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Comment;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Comment::class => true,
        ];
    }
}
