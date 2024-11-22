<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CsvParserService
{
    public function __construct(
        private ?UploadedFile $file,
    ) {
        if (!$file instanceof UploadedFile) {
            throw new BadRequestHttpException('File not found');
        }

        if ('csv' !== $file->getClientOriginalExtension()) {
            throw new BadRequestHttpException('Invalid file type');
        }
    }

    public function getData(): array
    {
        $csv = array_map('str_getcsv', file($this->file->getPathname()));
        $header = array_shift($csv);
        $data = [];
        foreach ($csv as $row) {
            $data[] = array_combine($header, $row);
        }

        return $data;
    }
}
