<?php

namespace App\Service;

use App\Entity\Homeagency;
use App\Entity\System;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;
    private $filesystem;

    public function __construct($targetDirectory, SluggerInterface $slugger, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->filesystem=$filesystem;
    }

    public function upload(Homeagency $homeagency, System $system, String $filecontent, String $fileExtension)
    {
        $new_file_path = $this->targetDirectory.$homeagency->getDirectoryname().$system->getDirectoryName().date('Ymdhis').".".$fileExtension;
        $this->filesystem->touch($new_file_path);
        $this->filesystem->chmod($new_file_path, 777);
        $this->filesystem->dumpFile($new_file_path, $filecontent);
        return $new_file_path;
    }

    public function deleteFile(String $filename){
        $this->filesystem->remove($filename);
        return $filename;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}