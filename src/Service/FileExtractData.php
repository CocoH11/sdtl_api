<?php


namespace App\Service;


use App\Controller\RefuelController;
use App\Entity\Homeagency;
use App\Entity\Product;
use App\Entity\Refuel;
use App\Entity\System;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileExtractData
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;
    private RefuelController $refuelcontroller;
    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, RefuelController $refuelController)
    {
        $this->manager=$manager;
        $this->validator=$validator;
        $this->refuelcontroller=$refuelController;
    }

    public function extractDataFromFile(File $file, System $system, Homeagency $homeagency, User $user, DateTime $creationdate)
    {
        $dataextracted=array();
        switch ($system->getName()){
            case "as24":
                return $this->extractDataFromFileAS24($file, $system, $homeagency, $dataextracted, $user, $creationdate);
                break;
            case "dkv":
                return$this->extractDataFromFileDKV($file, $system, $homeagency, $dataextracted, $user, $creationdate);
                break;
            case "uta":
                return $this->extractDataFromFileUTA($file, $system, $homeagency, $dataextracted, $user, $creationdate);
                break;
            case "ids":
                return $this->extractDataFromFileIDS($file, $system, $homeagency, $dataextracted, $user, $creationdate);
                break;
            case "laffon":
                return $this->extractDataFromFileLAFFON($file, $system, $homeagency, $dataextracted, $user, $creationdate);
                break;
            case "tokheim":
                return $this->extractDataFromFileTOKHEIM($file, $system, $homeagency, $dataextracted, $user, $creationdate);
                break;
        }
        return $dataextracted;

    }

    public function extractDataFromFileAS24(File $file, System $system, Homeagency $homeagency, array $refuelserrors, User $user, DateTime $creationdate){
        $readable=fopen($file->getPathname(), 'r');
        if ($readable) {
            $numLine=0;
            while (($buffer = fgets($readable)) !== false) {
                $line=preg_split("[\s{2,}]", $buffer);
                $date= DateTime::createFromFormat("YmdHi", substr($line[1], 0, 12));
                if (substr($line[1], 22, strlen($line[1])-22)==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
                $codecard=substr($line[1], 12, 4);
                $codedriver=substr($line[1], 16, 4);
                $volume=floatval(substr($line[2], 0, 4).".".substr($line[2], 4, 2));
                $stationlocation=substr($line[0], 13, strlen($line[0])-12);
                $mileage=intval(substr($line[2], 100, 9));
                $newrefuel=$this->refuelcontroller->createRefuel($stationlocation, $date, $codecard, $codedriver, $volume, $product, $mileage, $system, $homeagency, $user, $creationdate);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)array_push($refuelserrors, $this->buildErrorsTab($errors, $numLine));
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

    public function extractDataFromFileDKV(File $file, System $system, Homeagency $homeagency, array $refuelserrors, User $user, DateTime $creationdate){
    }

    public function extractDataFromFileUTA(File $file, System $system, Homeagency $homeagency, array $refuelserrors, User $user, DateTime $creationdate){
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
                $newrefuel=$this->refuelcontroller->createRefuel($stationlocation, $date, $codecard, $codedriver, $volume, $product, $mileage, $system, $homeagency, $user, $creationdate);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)array_push($refuelserrors, $this->buildErrorsTab($errors, $numLine));
                else $this->manager->persist($newrefuel);
                $numLine++;
            }
        }
        $this->manager->flush();
        return $refuelserrors;
    }

    public function extractDataFromFileLAFFON(File $file, System $system, Homeagency $homeagency, array $refuelserrors, User $user, DateTime $creationdate){
        $reader=new Xls();
        $spreadsheet=$reader->load($file->getPathname());
        $sheet=$spreadsheet->getActiveSheet();
        $numberLines=$sheet->getHighestRow();
        $blindmove=true;
        $numLine=0;
        $step=1;
        $numcard=null;
        while($numLine<$numberLines){
            $refCellvalue=strval($sheet->getCell("A".$numLine)->getValue());
            if ($blindmove && preg_match("/^[0-9]{5,5}$/", $refCellvalue)){
                $blindmove=false;
                $step=2;
                $numcard=$refCellvalue;
                $numLine++;
            }else if (!$blindmove && preg_match("/^TOTAL$/", $refCellvalue)){
                $blindmove=true;
                $step=1;
            }
            else if (!$blindmove){
                $date=Date::excelToDateTimeObject($refCellvalue);
                $codedriver=strval($sheet->getCell("C".$numLine)->getValue());
                $volume=floatval($sheet->getCell("F".$numLine)->getValue());
                $mileage=intval($sheet->getCell("H".$numLine)->getValue());
                if (strval($sheet->getCell("D".$numLine)->getValue())==$system->getDieselFileLabel())$product=$this->manager->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
                else $product=$this->manager->getRepository(Product::class)->findOneBy(["name", "ADBLUE"]);
                $newrefuel=$this->refuelcontroller->createRefuel($homeagency->getName(), $date, $numcard, $codedriver, $volume, $product, $mileage, $system, $homeagency, $user, $creationdate);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)array_push($refuelserrors, $this->buildErrorsTab($errors, $numLine));
                else $this->manager->persist($newrefuel);
            }
            $numLine+=$step;
        }
        $this->manager->flush();
        return $refuelserrors;
    }

    public function extractDataFromFileTOKHEIM(File $file, System $system, Homeagency $homeagency, array $refuelserrors, User $user, DateTime $creationdate){
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
                $newrefuel=$this->refuelcontroller->createRefuel($homeagency->getName(), $date, $buffer[1], $buffer[4], floatval($buffer[7]), $product, intval($buffer[2]), $system, $homeagency, $user, $creationdate);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)array_push($refuelserrors, $this->buildErrorsTab($errors, $numLine));
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

    public function extractDataFromFileIDS(File $file, System $system, Homeagency $homeagency, array $refuelserrors, User $user, DateTime $creationdate){
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
                $newrefuel=$this->refuelcontroller->createRefuel($buffer[9], $date, $buffer[12], $buffer[16], floatval($buffer[29]), $product,  $buffer[15], $system, $homeagency, $user, $creationdate);
                $errors=$this->validator->validate($newrefuel);
                if (count($errors)>0)array_push($refuelserrors, $this->buildErrorsTab($errors, $numLine));
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

    public function buildErrorsTab(ConstraintViolationListInterface $errors, int $numLine){
        $tab_errors=["numLine"=>$numLine, "errors_messages"=>[], "errors_codes"=>[]];
        for ($i=0; $i<count($errors); $i++){
            $constraint=$errors->get($i)->getConstraint();
            $error_code = isset($constraint->payload['error_code']) ? $constraint->payload['error_code'] : null;
            $error_message=$errors->get($i)->getMessage();
            array_push($tab_errors['errors_codes'], $error_code);
            array_push($tab_errors["errors_messages"], $error_message);
        }

        return $tab_errors;
    }

}