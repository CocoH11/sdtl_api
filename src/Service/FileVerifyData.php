<?php


namespace App\Service;


use App\Entity\Homeagency;
use App\Entity\System;
use Symfony\Component\HttpFoundation\File\File;

class FileVerifyData
{
    public function verifyDataFromFile(File $file, System $system, Homeagency $homeagency)
    {
        switch ($system->getName()){
            case "as24":
                return $this->verifyDataFromFileAS24($file, $system, $homeagency);
                break;
            case "dkv":
                return $this->verifyDataFromFileDKV($file, $system, $homeagency);
                break;
            case "uta":
                return $this->verifyDataFromFileUTA($file, $system, $homeagency);
                break;
            case "ids":
                return $this->verifyDataFromFileIDS($file, $system, $homeagency);
                break;
            case "laffon":
                return $this->verifyDataFromFileLAFFON($file, $system, $homeagency);
                break;
            case "tokheim":
                return $this->verifyDataFromFileTOKHEIM($file, $system, $homeagency);
                break;
        }

    }

    private function verifyDataFromFileAS24(File $file, System $system, Homeagency $homeagency)
    {
        return false;
    }

    private function verifyDataFromFileDKV(File $file, System $system, Homeagency $homeagency)
    {
        return false;
    }

    private function verifyDataFromFileUTA(File $file, System $system, Homeagency $homeagency)
    {
        return false;
    }

    private function verifyDataFromFileIDS(File $file, System $system, Homeagency $homeagency)
    {
        return false;
    }

    private function verifyDataFromFileLAFFON(File $file, System $system, Homeagency $homeagency)
    {
        return false;
    }

    private function verifyDataFromFileTOKHEIM(File $file, System $system, Homeagency $homeagency)
    {
        return false;
    }

}