<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use const PATHINFO_FILENAME;

class FileUpload
{
    public function __construct(
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectDir,
    ) {
    }

    public function uploadFile(UploadedFile|File|null $file, ?bool $force = false): string
    {
        if (null === $file) {
            throw new BadRequestHttpException('No file uploaded');
        }

        if (!$force) {
            $this->verifySize($file);
        }
        $path = $this->getFileName($file);
        $file->move($this->projectDir . '/public/medias', $path);

        return $path;
    }

    private function getFileName(UploadedFile|File $file): string
    {
        return match (true) {
            $file instanceof UploadedFile => strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
            $file instanceof File => strtolower(pathinfo($file->getFilename(), PATHINFO_FILENAME)) . '.png',
            default => throw new BadRequestHttpException('Invalid file type'),
        };
    }

    private function verifySize(UploadedFile|File $file): void
    {
        if ($file->getSize() > 1000000) {
            throw new BadRequestHttpException('File is too large');
        }
    }
}
