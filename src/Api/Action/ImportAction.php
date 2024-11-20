<?php

namespace App\Api\Action;

use App\Entity\Content;
use App\Entity\Upload;
use App\Service\FileUpload;
use App\Service\CsvParserService;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\SecurityBundle\Security;

#[AsController]
class ImportAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUpload             $fileUpload,
        private readonly Security               $security,
        private readonly Slug                   $slugService
    ) {}

    public function __invoke(Request $request)
    {
        $file = $request->files->get('file');
        $parserService = new CsvParserService($file);
        $data = $parserService->getData();

        $contents = [];
        foreach($data as $item) {
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

            $content->author = $this->security->getUser();

            $this->em->persist($content);
            $contents[] = $content;
        }

        $this->em->flush();

        return $contents;
    }
}
