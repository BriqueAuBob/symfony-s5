<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\IriConverterInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Routing\IriConverter;
use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Comment;
use App\Entity\Content;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\UuidV4;

final readonly class CreateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security               $security,
        private IriConverterInterface  $iriConverter
    ) {
    }

    /** @param Comment $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comment {
        $slug = $uriVariables['slug'];

        $data->id = UuidV4::v4();
        $data->author = $this->security->getUser();
        $content = $this->em->getRepository(Content::class)->findOneBy(['slug' => $slug]);

        if($data->content === null) {
            throw new \Exception('Content not found');
        }

        $data->content = $content;

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}
