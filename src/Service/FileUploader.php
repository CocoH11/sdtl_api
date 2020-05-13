<?php

namespace App\Service;

use PhpParser\Node\Scalar\String_;
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

    public function upload(String $filename, String $filecontent)
    {
        $new_file_path = $this->targetDirectory . $filename;
        $this->filesystem->touch($new_file_path);
        $this->filesystem->dumpFile($new_file_path, $filecontent);
        //Droits du fichier: lecture, écriture, execution
        $this->filesystem->chmod($new_file_path, 0777);
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