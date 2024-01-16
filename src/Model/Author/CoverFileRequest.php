<?php

namespace App\Model\Author;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class CoverFileRequest
{
    #[NotNull]
    #[Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg'])]
    private UploadedFile $cover;

    public function setCover(UploadedFile $file): static
    {
        $this->cover = $file;

        return $this;
    }

    public function getCover(): UploadedFile
    {
        return $this->cover;
    }
}
