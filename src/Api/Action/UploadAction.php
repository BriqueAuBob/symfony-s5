<?php

namespace App\Api\Action;

use App\Entity\Upload;
use App\Service\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class UploadAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
        private FileUpload $fileUpload,
    ) {
    }

    public function __invoke(Request $request)
    {
        $file = $request->files->get('file');
        $path = $this->fileUpload->uploadFile($file);

        $upload = new Upload();
        $upload->path = "/medias/{$path}";
        $upload->createdAt = new \DateTime();
        $upload->updatedAt = new \DateTime();

        return $upload;
    }
}
