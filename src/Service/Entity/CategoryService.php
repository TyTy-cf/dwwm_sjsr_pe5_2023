<?php

namespace App\Service\Entity;

use App\Entity\Category;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryService
{

    public function __construct(
        private FileUploader $fileUploader
    ) { }

    /**
     * @param UploadedFile|null $uploadedFile
     * @param Category $category
     */
    public function handleFileUpload(?UploadedFile $uploadedFile, Category $category): void {
        if ($uploadedFile !== null) {
            $category->setImage(
                $this->fileUploader->uploadFile(
                    $uploadedFile,
                    '/category'
                )
            );
        }
    }

}
