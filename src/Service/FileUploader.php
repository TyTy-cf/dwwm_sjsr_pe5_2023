<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    /**
     * FileUploader constructor.
     * @param string $publicUploadsDir complete path to default directory of uploaded files
     * @param string $uploadsDir default directory of uploaded files
     * See : config/services.yaml and .env
     */
    public function __construct(
        private string $publicUploadsDir,
        private string $uploadsDir,
        private string $publicDir,
    ) { }

    /**
     * @param UploadedFile $uploadedFile le fichier uploadé
     * @param string $dir le dossier où déplacer le fichier (dans l'application)
     * @return string
     */
    public function uploadFile(UploadedFile $uploadedFile, string $dir = ''): string
    {
        $destination = $this->publicUploadsDir.$dir;
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);
        return '/'.$this->uploadsDir.$dir.'/'.$newFilename;
    }

    public function cleanUnusedFiles(string $oldFile): void {
        $fs = new Filesystem();
        $fileName = $this->publicDir . $oldFile;
        if ($fs->exists($fileName)) {
            $fs->remove($fileName);
        }
    }

}
