<?php
declare(strict_types=1);

namespace App\Test;

use App\Entity\Image;
use App\Service\FileUploadService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadServiceTest extends KernelTestCase
{
    /** @var FileUploadService */
    private $fileUploadService;
    private $image;
    private $file;
    private $uploadDir;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->uploadDir = self::$kernel->getContainer()->getParameter('app.upload_dir');
        $this->fileUploadService = self::$kernel->getContainer()->get(FileUploadService::class);
        $this->file = $this->createTempFile();
        $this->image = $this->createUploadedFile($this->file);
    }

    private function createTempFile(): string
    {
        $file = tempnam(sys_get_temp_dir(), 'img_').'.png'; // create file on the system
        imagepng(imagecreatetruecolor(15, 15), $file); // create the image and store it

        return $file;
    }

    private function createUploadedFile(string $file): UploadedFile
    {
        return new UploadedFile(
            $file,
            basename($file),
            'image/png',
            filesize($file),
            null,
            true
        );
    }

    /** @test */
    public function checkUploadSingleFile()
    {
        $this->assertNotNull($this->file);
        $this->assertNotNull($this->image);
        $this->assertEquals($this->image->getClientMimeType(), 'image/png');

        $imageEntity = $this->fileUploadService->upload($this->image);
        $this->assertEquals($imageEntity->getName(), basename($this->file));
        $this->assertNull($imageEntity->getTitle());
        $this->assertStringStartsWith($this->uploadDir, $imageEntity->getPath());
        $this->assertStringEndsWith('.png', $imageEntity->getPath());
        $imagePath = dirname($this->fileUploadService->getUploadDir()).'/'.$imageEntity->getPath();
        $this->assertTrue(file_exists($imagePath));
        unlink($imagePath);
    }

    /** @test */
    public function checkUploadSingleFileWithTile()
    {
        $imageEntity = $this->fileUploadService->upload($this->image, 'My image');
        $this->assertEquals($imageEntity->getTitle(), 'My image');

        $imagePath = dirname($this->fileUploadService->getUploadDir()).'/'.$imageEntity->getPath();
        unlink($imagePath);
    }

    /** @test */
    public function checkUploadSingleFileUsingMultipleMethod()
    {
        $images = $this->fileUploadService->multipleUpload([
            ['file' => $this->image],
        ]);

        $this->assertCount(1, $images);
        $this->assertEquals($images->first()->getName(), basename($this->file));
        $this->assertNull($images->first()->getTitle());
        $this->assertStringStartsWith($this->uploadDir, $images->first()->getPath());
        $this->assertStringEndsWith('.png', $images->first()->getPath());
        $imagePath = dirname($this->fileUploadService->getUploadDir()).'/'.$images->first()->getPath();
        $this->assertTrue(file_exists($imagePath));
        unlink($imagePath);
    }

    /** @test */
    public function checkMultipleMethod()
    {
        $files = [$this->file, $this->createTempFile()];
        $image2 = $this->createUploadedFile($files[1]);
        $images = $this->fileUploadService->multipleUpload([
            ['file' => $this->image],
            ['file' => $image2, 'title' => 'My title'],
        ]);

        $this->assertCount(2, $images);

        /** @var Image $imageEntity */
        foreach ($images as $index => $imageEntity) {
            $this->assertEquals($imageEntity->getName(), basename($files[$index]));
            $this->assertStringStartsWith($this->uploadDir, $imageEntity->getPath());
            $this->assertStringEndsWith('.png', $imageEntity->getPath());
            $imagePath = dirname($this->fileUploadService->getUploadDir()).'/'.$imageEntity->getPath();
            $this->assertTrue(file_exists($imagePath));
            unlink($imagePath);
        }

        $this->assertNull($images->first()->getTitle());
        $this->assertEquals($images->last()->getTitle(), 'My title');
    }

    /** @test */
    public function checkMultipleMethodReturnsEmptyCollectionForWrongData()
    {
        $images = $this->fileUploadService->multipleUpload([]);
        $this->assertCount(0, $images);

        $images = $this->fileUploadService->multipleUpload([
            ['fileS' => $this->image] // Wrong key
        ]);
        $this->assertCount(0, $images);

        $images = $this->fileUploadService->multipleUpload([
            ['fileS' => $this->image], // Wrong key
            ['fil'   => $this->image], // Wrong key
        ]);
        $this->assertCount(0, $images);

        $images = $this->fileUploadService->multipleUpload([
            ['file' => $this->image], // Correct key
            ['fil'  => $this->image], // Wrong key
        ]);
        $this->assertCount(1, $images);

        $imagePath = dirname($this->fileUploadService->getUploadDir()).'/'.$images->first()->getPath();
        $this->assertTrue(file_exists($imagePath));
        unlink($imagePath);
    }
}