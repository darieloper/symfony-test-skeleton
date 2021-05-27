<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadServiceInterface
{
    public function upload(UploadedFile $file, ?string $title = null): Image;

    public function multipleUpload(array $data): Collection;
}