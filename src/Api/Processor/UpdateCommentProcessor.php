<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateContent;
use App\Entity\Comment;
use App\Entity\Content;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;

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

        if($comment === null) {
            throw new \Exception('Comment not found');
        }

        $comment->text = $data->text;

        $this->em->flush();

        return $comment;
    }
}
