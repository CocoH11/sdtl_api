<?php


namespace App\Service;


use App\Entity\Homeagency;
use App\Entity\Refuel;
use App\Entity\System;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileExtractData
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
     $this->manager=$manager;
    }

    public function extractDataFromFile(File $file, System $system, Homeagency $homeagency)
    {
        switch ($system->getName()){
            case "as24":
                $this->extractDataFromFileAS24($file, $system, $homeagency);
                break;
            case "dkv":
                $this->extractDataFromFileDKV($file, $system, $homeagency);
                break;
            case "uta":
                $this->extractDataFromFileUTA($file, $system, $homeagency);
                break;
            case "ids":
                $this->extractDataFromFileIDS($file, $system, $homeagency);
                break;
            case "laffon":
                $this->extractDataFromFileLAFFON($file, $system, $homeagency);
                break;
            case "tokheim":
                $this->extractDataFromFileTOKHEIM($file, $system, $homeagency);
                break;
        }

    }

    public function extractDataFromFileAS24(File $file, System $system, Homeagency $homeagency){
        $readable=fopen($file->getPathname(), 'r');
        var_dump($file->getPathname());
        if ($readable) {
            while (($buffer = fgets($readable)) !== false) {
                $line=preg_split("[\s{2,}]", $buffer);
                $new_refuel=new Refuel();
                $date= DateTime::createFromFormat("YmdHi", substr($line[1], 0, 12));
                var_dump($line[1]);
                $new_refuel->setDate($date);
                $new_refuel->setCodeCard(substr($line[1], 12, 4));
                $new_refuel->setCodeDriver(substr($line[1], 16, 4));
                $new_refuel->setVolume(floatval(substr($line[2], 0, 4).".".substr($line[2], 4, 2)));
                $new_refuel->setStationLocation(substr($line[0], 13, strlen($line[0])-12));
                $new_refuel->setTypeProduit(substr($line[1], 22, strlen($line[1])-22));
                $new_refuel->setSystem($system);
                $new_refuel->setHomeagency($homeagency);
                $this->manager->persist($new_refuel);
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }
        return "";
    }

    public function extractDataFromFileDKV(File $file, System $system, Homeagency $homeagency){
        return "";
    }

    public function extractDataFromFileUTA(File $file, System $system, Homeagency $homeagency){
        return "";
    }

    public function extractDataFromFileLAFFON(File $file, System $system, Homeagency $homeagency){
        return "";
    }

    public function extractDataFromFileTOKHEIM(File $file, System $system, Homeagency $homeagency){
        return "";
    }

    public function extractDataFromFileIDS(File $file, System $system, Homeagency $homeagency){

    }

}