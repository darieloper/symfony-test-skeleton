<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService implements FileUploadServiceInterface
{
    protected $absolutePath;
    protected $uploadDir;

    public function __construct($absolutePath, $uploadDir)
    {
        $this->absolutePath = $absolutePath;
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $file, ?string $title = null): Image {
        $image = new Image();
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $image->setPath($this->uploadDir . '/' . $fileName)
            ->setName($file->getClientOriginalName());

        if (isset($title)) {
            $image->setTitle($title);
        }

        // Create directory if it doesn't exist
        if (!file_exists($this->absolutePath)) {
            mkdir($this->absolutePath);
        }

        $file->move($this->absolutePath, $fileName);
        return $image;
    }

    public function multipleUpload(array $data): Collection {
        $images = new ArrayCollection();
        foreach ($data as $fileData) {
            if (!isset($fileData['file'])) {
                continue;
            }
            $images->add($this->upload($fileData['file'], $fileData['title'] ?? null));
        }

        return $images;
    }

    public function getUploadDir() {
        return $this->absolutePath;
    }
}