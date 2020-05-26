<?php


namespace App\Service;


use App\Entity\Homeagency;
use App\Entity\Product;
use App\Entity\Refuel;
use App\Entity\System;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
            $numLine=0;
            while (($buffer = fgets($readable)) !== false) {
                $line=preg_split("[\s{2,}]", $buffer);
                $newrefuel=new Refuel();
                $date= DateTime::createFromFormat("YmdHi", substr($line[1], 0, 12));
                if (substr($line[1], 22, strlen($line[1])-22)==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $codecard=substr($line[1], 12, 4);
                $codedriver=substr($line[1], 16, 4);
                $volume=floatval(substr($line[2], 0, 4).".".substr($line[2], 4, 2));
                $stationlocation=substr($line[0], 13, strlen($line[0])-12);
                $mileage=intval(substr($line[2], 100, 9));
                $this->createRefuel($stationlocation, $date, $codecard, $codedriver, $volume, $product, $mileage, $system, $homeagency);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)$this->buildErrorsTab($refuelserrors, $errors, $numLine);
                else $this->manager->persist($newrefuel);
                $numLine++;
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }
        return $refuelserrors;
    }

    public function extractDataFromFileDKV(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        return "";
    }

    public function extractDataFromFileUTA(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $reader=new Xlsx();
        $spreadsheet=$reader->load($file->getPathname());
        $sheet=$spreadsheet->getActiveSheet();
        $numLine=0;
        for ($i=3; $i<$sheet->getHighestRow()+1; $i++){
            $productLabel=strval($sheet->getCell("M".$i)->getValue());
            if ($productLabel==$system->getDieselFileLabel() || $productLabel==$system->getAdblueFielLabel()){
                $date=DateTime::createFromFormat("d.m.YH:i:s", $sheet->getCell("A".$i)->getValue().$sheet->getCell("B".$i)->getValue());
                if ($productLabel==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $newrefuel= new Refuel();
                $codecard=strval($sheet->getCell("I".$i)->getValue());
                $codedriver=strval($sheet->getCell("I".$i)->getValue());
                $volume=floatval($sheet->getCell("O".$i)->getValue());
                $mileage=floatval($sheet->getCell("J".$i)->getValue());
                $stationlocation=$sheet->getCell("E".$i)->getValue();
                $newrefuel=$this->createRefuel($stationlocation, $date, $codecard, $codedriver, $volume, $product, $mileage, $system, $homeagency);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)$this->buildErrorsTab($refuelserrors, $errors, $numLine);
                else $this->manager->persist($newrefuel);
                $numLine++;
            }
        }
        $this->manager->flush();
        return $refuelserrors;
    }

    public function extractDataFromFileLAFFON(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
    }

    public function extractDataFromFileTOKHEIM(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $readable=fopen($file->getPathname(), 'r');
        $count=0;
        if ($readable){
            $numLine=0;
            while(($buffer=fgetcsv($readable, 1000, ";")) !==false){
                if ($count==0){
                    $count++;
                    continue;
                }
                $date=DateTime::createFromFormat("d/m/YH:i:s", $buffer[5].$buffer[6]);
                if ($buffer[0]==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $newrefuel=$this->createRefuel($homeagency->getName(), $date, $buffer[1], $buffer[4], floatval($buffer[7]), product, intval($buffer[2]), $system, $homeagency);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)$this->buildErrorsTab($refuelserrors, $errors, $numLine);
                else $this->manager->persist($newrefuel);
                $numLine++;
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }
        return $refuelserrors;
    }

    public function extractDataFromFileIDS(File $file, System $system, Homeagency $homeagency, array $refuelserrors){
        $readable=fopen($file->getPathname(), 'r');
        $count=0;
        if ($readable){
            $numLine=0;
            while (($buffer=fgetcsv($readable, 1000, "\t"))!==false){
                if ($count==0){
                    $count++;
                    continue;
                }
                $date=DateTime::createFromFormat("d/m/YH:i:s", $buffer[13].$buffer[14]);
                if ($buffer[28]==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $newrefuel=$this->createRefuel($buffer[9], $date, $buffer[12], $buffer[16], floatval($buffer[29]), $product,  $buffer[15], $system, $homeagency);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)$this->buildErrorsTab($refuelserrors, $errors, $numLine);
                else $this->manager->persist($newrefuel);
                $numLine++;
            }
            $this->manager->flush();
            if (!feof($readable)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($readable);
        }
        return $refuelserrors;
    }

    public function convertXLSXtoCSV(String $pathname){
        $reader=new Xlsx();
        $spreadsheet=$reader->load($pathname);
        $fileCsv=new Csv($spreadsheet);
        $fileCsv->save("hello");
    }

    public function buildErrorsTab(array $refuelserrors, ConstraintViolationListInterface $errors, int $numLine){
        $tab_errors=[];
        for ($i=0; $i<count($errors); $i++){
            array_push($tab_errors,$errors->get($i)->getMessage());
        }
        array_push($refuelserrors, [$numLine, $tab_errors]);
        return $tab_errors;
    }

    public function createRefuel(String $stationlocation, DateTime $date, String $codecard, String $codedriver, Float $volume, Product $product, int $mileage, System $system, Homeagency $homeagency): Refuel{
        $newrefuel=new Refuel();
        $newrefuel
            ->setCodeCard($codecard)
            ->setCodeDriver($codedriver)
            ->setDate($date)
            ->setVolume($volume)
            ->setMileage($mileage)
            ->setProduct($product)
            ->setStationLocation($stationlocation)
            ->setHomeagency($homeagency)
            ->setSystem($system);
        return $newrefuel;
    }

}