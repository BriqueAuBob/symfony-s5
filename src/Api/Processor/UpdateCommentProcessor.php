<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

final readonly class UpdateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /** @param Comment $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comment {
        $id = $uriVariables['id'];
        $comment = $this->em->getRepository(Comment::class)->find($id);

        if (null === $comment) {
            throw new Exception('Comment not found');
        }

        $comment->text = $data->text;

        $this->em->flush();

        return $comment;
    }
}
