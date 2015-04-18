<?php

namespace AirSim\Bundle\CoreBundle\Services;


use Symfony\Component\Filesystem\Filesystem;

class FileService
{
    private static $fileServiceInstance;

    // Dependencies
    private $fileSystem = null;

    private function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    public static function getInstance()
    {
        if(is_null(self::$fileServiceInstance))
        {
            self::$fileServiceInstance = new self();
        }
        return self::$fileServiceInstance;
    }

    public function uploadFiles($filesToBeUploaded, $fileValidFormats, $uploadDirectory, $receiverId = null) {
        $uploadedFiles = array();

        foreach($filesToBeUploaded as $file) {

            $extension = $file->getClientOriginalExtension();
            $originalFileName = preg_replace("/\.[^.]+$/", "", $file->getClientOriginalName());

            if(in_array($extension, $fileValidFormats)) {
                if($receiverId != null) {
                    $fileName = $originalFileName.'_user'.$receiverId.'_'.(time()*1000).'.'.$extension;
                }
                else {
                    $fileName = $originalFileName.'_'.(time()*1000).'.'.$extension;
                }

                $file->move($uploadDirectory, $fileName);

                $uploadedFiles[] = $fileName;
            }
        }

        return $uploadedFiles;
    }

    public function deleteFile($filePath) {
        $this->fileSystem->remove($filePath);
    }

    public function moveFile($sourcePath, $destinationPath) {
        $this->fileSystem->copy($sourcePath, $destinationPath);
        $this->fileSystem->remove($sourcePath);
    }


}