<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(String $filename, String $filecontent)
    {
        $filesystem= new Filesystem();
        $new_file_path = $this->targetDirectory . $filename;
        $filesystem->touch($new_file_path);
        $filesystem->dumpFile($new_file_path, $filecontent);
        return $filename;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}