<?php

namespace App\Controller;

use App\Entity\Homeagency;
use App\Entity\Product;
use App\Entity\Refuel;
use App\Entity\User;
use App\Entity\System;
use App\Service\FileExtractData;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RefuelController
 * @package App\Controller
 * @Route("/api")
 */
class RefuelController extends AbstractController
{
    /**
     * @Route("/refuel", name="addRefuel", methods={"PUT"})
     */
    public function addRefuel(Request $request, ValidatorInterface $validator){
    }
    /**
     * @Route("/refuels", name="addRefuels", methods={"PUT"})
     */
    public function addRefuels(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);
        $homeagency=$this->checkHomeAgency();
        $refuelserrors=[];
        $system=$this->getDoctrine()->getRepository(System::class)->find(1);
        foreach ($data["refuels"] as $refuel){
            $system=$this->getDoctrine()->getRepository(System::class)->find($refuel["system"]);
            if ($refuel["product"]==$system->getDieselFileLabel())$product=$this->getDoctrine()->getRepository(Product::class)->findOneBy(["name"=>"DIESEL"]);
            else $product=$this->getDoctrine()->getRepository(Product::class)->findOneBy(["name"=>"ADBLUE"]);
            $newrefuel=new Refuel();
            $newrefuel->setCodeCard($refuel["codecard"]);
            $newrefuel->setCodeDriver($refuel["codedriver"]);
            $newrefuel->setVolume($refuel["volume"]);
            $newrefuel->setMileage($refuel["mileage"]);
            $newrefuel->setStationLocation($refuel["stationlocation"]);
            $newrefuel->setDate(\DateTime::createFromFormat("m-d-Y", $refuel["date"]));
            $newrefuel->setProduct($product);
            $newrefuel->setSystem($system);
            $newrefuel->setHomeagency($homeagency);
            $refuelerrors=$validator->validate($newrefuel);
            if (count($refuelserrors)==0)$this->getDoctrine()->getManager()->persist($newrefuel);
            array_push($refuelserrors, $refuelerrors);
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($refuelserrors);

    }

    /**
     * @Route("/refuel/{id}", name="deleteRefuel", methods={"DELETE"})
     */
    public function deleteRefuel(Request $request, int $id){
    }

    /**
     * @Route("/refuel/{id}", name="updateRefuel", methods={"PATCH"})
     */
    public function updateRefuel(Request $request,int $id){}

    /**
     * @Route("/refuel/file", name="addFileRefuel", methods={"PUT"})
     */
    public function addFileRefuel(Request $request, FileUploader $fileUploader, FileExtractData $fileExtractData){
        /*Data*/
        $data= json_decode($request->getContent(), true);
        $numSystem=$data["system"];
        $filedata=base64_decode($data["data"]);
        $fileExtension=$data["fileExtension"];
        /*Check the System and the HomeAgency*/
        $homeagency=$this->checkHomeAgency();
        $system=$this->checkSystem($numSystem);
        /*Save File*/
        $newFileName=$fileUploader->upload($homeagency, $system, $filedata, $fileExtension);


        /*Extract Data*/
        $file=new File($newFileName);
        $fileExtractData->extractDataFromFile($file, $system, $homeagency);
        return new JsonResponse($this->getUser()->getRoles());
    }

    public function checkSystem(int $sysValue){
        $system=$this->getDoctrine()->getRepository(System::class)->find($sysValue);
        return $system;
    }

    public function checkHomeAgency(){
        $user= $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $homeagency=$user->getHomeAgency();
        return $homeagency;
    }

    public function checkMimeType(File $file){
        $mediaType="";
        switch ($file->getMimeType()){
            case "text/csv":
                $mediaType="csv";
                break;
            case "application/vnd.ms-excel":
                $mediaType="xls";
                break;
            case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                $mediaType="xlsx";
                break;
        }
        return $mediaType;
    }
    /**
     * @Route("/refuel", name="testSecureLogin", methods={"POST"})
     */
    public function testSecureLogin(){
        return new Response("testSecureLogin");
    }
}
