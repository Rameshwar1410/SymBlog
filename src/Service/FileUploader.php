<?php
declare(strict_types = 1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileUploader for user image uploaded file handling
 */
class FileUploader
{
    /** 
     * Used to uploaded image move to image directory
     * 
     * @param UploadedFile $image An instance of UploadedFile
     * @param string $imagePath Uploaded image store directory path
     * @return string $fileName Random created file name
     */
    public function profileImage(UploadedFile $image, string $imagePath): string
    {
        $fileName = md5(uniqid()) . '.' . $image->guessExtension();
        $image->move(
            $imagePath,
            $fileName
        );

        return $fileName;
    }
}
