<?php

namespace App\Service\Author;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

readonly class UploadedService
{
    public function __construct(private string $uploadDir)
    {
    }

    public function uploadBookCover(int $id, UploadedFile $uploadedFile): string
    {
        $unique = Uuid::v4()->toRfc4122() . '.' . $uploadedFile->guessExtension() ?? 'png';
        $uploadPath = $this->uploadDir . 'book' . DIRECTORY_SEPARATOR . $id;

        $uploadedFile->move($uploadPath, $unique);

        return $uploadPath . DIRECTORY_SEPARATOR . $unique;
    }
}
