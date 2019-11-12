<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;

/**
 * Used to test FileUploader service
 * 
 * @coversDefaultClass App\Service\FileUploader
 */
class FileUploaderTest extends TestCase
{
    /**
     * Used to test profileImage method
     * 
     * @covers::profileImage
     */
    public function testProfileImage()
    {
        $image = \Mockery::mock(UploadedFile::class);
        $fileUploader = new FileUploader();
        $image->shouldReceive('guessExtension')
            ->once()
            ->with()
            ->andReturn('png');
        $image->shouldReceive('move')
            ->once()
            ->with('/image', '57a1db0211c961f470569ef5e2a9c5a0.png')
            ->andReturnSelf();
        $fileName = $fileUploader->profileImage($image, '/image');
        $this->assertEquals('57a1db0211c961f470569ef5e2a9c5a0.png', $fileName);
    }
}

namespace App\Service;

function uniqid()
{
    return 12411;
}

function md5()
{
    return "57a1db0211c961f470569ef5e2a9c5a0";
}
