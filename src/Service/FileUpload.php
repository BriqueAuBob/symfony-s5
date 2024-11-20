<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileUpload
{
    public function __construct(
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {}

    private function getFileName(UploadedFile $file): string
    {
        $originalFilename = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        return $originalFilename.'-'.uniqid().'.'.$file->getClientOriginalExtension();
    }

    private function verifySize(UploadedFile $file): void
    {
        if ($file->getSize() > 1000000) {
            throw new BadRequestHttpException('File is too large');
        }
    }

    public function uploadFile(?UploadedFile $file): string
    {
        if($file === null) {
            throw new BadRequestHttpException('No file uploaded');
        }

        $this->verifySize($file);
        $path = $this->getFileName($file);
        $file->move($this->projectDir.'/public/medias', $path);

        return $path;
    }
}
