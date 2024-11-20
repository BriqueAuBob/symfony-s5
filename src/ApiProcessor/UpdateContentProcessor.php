<?php declare(strict_types=1);

namespace App\ApiProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\CreateContent;
use App\Entity\Content;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Slug $slugService,
    ) {
    }

    /** @param CreateContent $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {
        $oldSlug = $context['previous_data']->slug;
        $content = $this->slugService->getEntityWithSlug($oldSlug);

        if($content === null) {
            throw new \Exception('Content not found');
        }

        if($content->title === $data->title) {
            $content->slug = $oldSlug;
        } else {
            $content->slug = $this->slugService->get($data->title);
        }
        $content->title = $data->title;
        $content->content = $data->content;
        $content->thumbnail = $data->thumbnail;

        $this->em->flush();

        return $content;
    }
}
