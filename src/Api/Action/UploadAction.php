<?php declare(strict_types=1);

namespace App\Api\Action;

use App\Entity\Upload;
use App\Service\FileUpload;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UploadAction
{
    public function __construct(
        private FileUpload $fileUpload,
    ) {
    }

    public function __invoke(Request $request)
    {
        $file = $request->files->get('file');
        $path = $this->fileUpload->uploadFile($file);

        $upload = new Upload();
        $upload->path = "/medias/{$path}";

        return $upload;
    }
}
