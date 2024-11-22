<?php declare(strict_types=1);

namespace App\Api\Action;

use App\Entity\Content;
use App\Entity\Meta;
use App\Entity\Upload;
use App\Service\CsvParserService;
use App\Service\FileUpload;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ImportAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUpload $fileUpload,
        private readonly Security $security,
        private readonly Slug $slugService,
    ) {
    }

    public function __invoke(Request $request)
    {
        $file = $request->files->get('file');
        $parserService = new CsvParserService($file);
        $data = $parserService->getData();

        $contents = [];
        foreach ($data as $item) {
            $thumbnail = file_get_contents($item['cover']);
            $tempFilePath = tempnam(sys_get_temp_dir(), 'thumb');
            file_put_contents($tempFilePath, $thumbnail);

            $thumbnail = new File($tempFilePath, true);
            $path = $this->fileUpload->uploadFile($thumbnail);

            $upload = new Upload();
            $upload->path = $path;
            $this->em->persist($upload);

            $content = new Content();
            $content->title = $item['title'];
            $content->content = $item['content'];
            $content->thumbnail = $upload;
            $content->slug = $this->slugService->get($item['title']);

            foreach (['title', 'description'] as $metaTag) {
                $meta = new Meta();
                $meta->name = $metaTag;
                $meta->value = $item['meta_' . $metaTag];
                $content->addMeta($meta);

                $this->em->persist($meta);
            }

            $content->author = $this->security->getUser();

            $this->em->persist($content);
            $contents[] = $content;
        }

        $this->em->flush();

        return $contents;
    }
}
