<?php


namespace App\Service;


use App\Entity\Homeagency;
use App\Entity\Product;
use App\Entity\Refuel;
use App\Entity\System;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileExtractData
{
    private $manager;
    private $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
     $this->manager=$manager;
     $this->validator=$validator;
    }

    public function extractDataFromFile(File $file, System $system, Homeagency $homeagency)
    {
        $refuelserrors=[];
        switch ($system->getName()){
            case "as24":
                $this->extractDataFromFileAS24($file, $system, $homeagency, $refuelserrors);
                break;
            case "dkv":
                $this->extractDataFromFileDKV($file, $system, $homeagency, $refuelserrors);
                break;
            case "uta":
                $this->extractDataFromFileUTA($file, $system, $homeagency, $refuelserrors);
                break;
            case "ids":
                $this->extractDataFromFileIDS($file, $system, $homeagency, $refuelserrors);
                break;
            case "laffon":
                $this->extractDataFromFileLAFFON($file, $system, $homeagency, $refuelserrors);
                break;
            case "tokheim":
                $this->extractDataFromFileTOKHEIM($file, $system, $homeagency, $refuelserrors);
                break;
        }
        return $refuelserrors;

    }

    public function extractDataFromFileAS24(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $readable=fopen($file->getPathname(), 'r');
        if ($readable) {
            while (($buffer = fgets($readable)) !== false) {

                $line=preg_split("[\s{2,}]", $buffer);
                $new_refuel=new Refuel();
                $date= DateTime::createFromFormat("YmdHi", substr($line[1], 0, 12));
                if (substr($line[1], 22, strlen($line[1])-22)==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $new_refuel->setDate($date);
                $new_refuel->setCodeCard(substr($line[1], 12, 4));
                $new_refuel->setCodeDriver(substr($line[1], 16, 4));
                $new_refuel->setVolume(floatval(substr($line[2], 0, 4).".".substr($line[2], 4, 2)));
                $new_refuel->setStationLocation(substr($line[0], 13, strlen($line[0])-12));
                $new_refuel->setProduct($product);
                $new_refuel->setMileage(intval(substr($line[2], 100, 9)));
                var_dump(substr($line[2], 100, 9));
                $new_refuel->setSystem($system);
                $new_refuel->setHomeagency($homeagency);
                $refuelerrors=$this->validator->validate($new_refuel);
                if (count($refuelserrors)==0)$this->manager->persist($new_refuel);
                array_push($refuelserrors, $refuelerrors);
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }
        return "";
    }

    public function extractDataFromFileDKV(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        return "";
    }

    public function extractDataFromFileUTA(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $reader=new Xlsx();
        $spreadsheet=$reader->load($file->getPathname());
        $sheet=$spreadsheet->getActiveSheet();

        for ($i=3; $i<$sheet->getHighestRow()+1; $i++){
            var_dump($sheet->getCell("E".$i)->getValue());
            $productLabel=strval($sheet->getCell("M".$i)->getValue());
            if ($productLabel==$system->getDieselFileLabel() || $productLabel==$system->getAdblueFielLabel()){
                $date=DateTime::createFromFormat("d.m.YH:i:s", $sheet->getCell("A".$i)->getValue().$sheet->getCell("B".$i)->getValue());
                if ($productLabel==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $newrefuel= new Refuel();
                $newrefuel->setCodeCard(strval($sheet->getCell("I".$i)->getValue()));
                $newrefuel->setCodeDriver(strval($sheet->getCell("I".$i)->getValue()));
                $newrefuel->setVolume(floatval($sheet->getCell("O".$i)->getValue()));
                $newrefuel->setMileage(floatval($sheet->getCell("J".$i)->getValue()));
                $newrefuel->setStationLocation($sheet->getCell("E".$i)->getValue());
                $newrefuel->setDate($date);
                $newrefuel->setProduct($product);
                $newrefuel->setSystem($system);
                $newrefuel->setHomeagency($homeagency);
                $refuelerrors=$this->validator->validate($newrefuel);
                if (count($refuelserrors)==0)$this->manager->persist($newrefuel);
                array_push($refuelserrors, $refuelerrors);
            }
        }
        $this->manager->flush();
        return "";
    }

    public function extractDataFromFileLAFFON(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        return "";
    }

    public function extractDataFromFileTOKHEIM(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $readable=fopen($file->getPathname(), 'r');
        $count=0;
        if ($readable){
            while(($buffer=fgetcsv($readable, 1000, ";")) !==false){
                if ($count==0){
                    $count++;
                    continue;
                }
                $date=DateTime::createFromFormat("d/m/YH:i:s", $buffer[5].$buffer[6]);
                if ($buffer[0]==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $new_refuel=new Refuel();
                $new_refuel->setCodeCard($buffer[1]);
                $new_refuel->setCodeDriver($buffer[4]);
                $new_refuel->setDate($date);
                $new_refuel->setProduct($product);
                $new_refuel->setMileage(intval($buffer[2]));
                $new_refuel->setVolume(floatval($buffer[7]));
                $new_refuel->setStationLocation($homeagency->getName());
                $new_refuel->setSystem($system);
                $new_refuel->setHomeagency($homeagency);
                $refuelerrors=$this->validator->validate($new_refuel);
                if (count($refuelserrors)==0)$this->manager->persist($new_refuel);
                array_push($refuelserrors, $refuelerrors);
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }
        return "";
    }

    public function extractDataFromFileIDS(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $readable=fopen($file->getPathname(), 'r');
        $count=0;
        if ($readable){
            while (($buffer=fgetcsv($readable, 1000, "\t"))!==false){
                if ($count==0){
                    $count++;
                    continue;
                }
                $date=DateTime::createFromFormat("d/m/YH:i:s", $buffer[13].$buffer[14]);
                if ($buffer[28]==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $new_refuel=new Refuel();
                $new_refuel->setStationLocation($buffer[9]);
                $new_refuel->setDate($date);
                $new_refuel->setCodeCard($buffer[12]);
                $new_refuel->setCodeDriver($buffer[16]);
                $new_refuel->setVolume(floatval($buffer[29]));
                $new_refuel->setProduct($product);
                $new_refuel->setMileage($buffer[15]);
                $new_refuel->setSystem($system);
                $new_refuel->setHomeagency($homeagency);
                $refuelerrors=$this->validator->validate($new_refuel);
                if (count($refuelserrors)==0)$this->manager->persist($new_refuel);
                array_push($refuelserrors, $refuelerrors);
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }


    }

}