<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Refuel;
use App\Entity\User;
use App\Entity\System;
use App\Service\FileExtractData;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RefuelController
 * @property ManagerRegistry getDoctrine
 * @package App\Controller
 * @Route("/api")
 */
class RefuelController extends AbstractController
{
    /**
     * @Route("/refuels", name="addRefuels", methods={"PUT"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function addRefuels(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $homeagency=$this->checkHomeAgency();
        $user= $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $refuelserrors=[];
        $numLine=0;
        foreach ($data["refuels"] as $refuel){
            $system=$this->getDoctrine()->getRepository(System::class)->find($refuel["system"]);
            $product=$this->getDoctrine()->getRepository(Product::class)->find($refuel["product"]);
            $date=\DateTime::createFromFormat("m-d-Y", $refuel["date"]);
            $creationDate=new \DateTime();
            if (!$date)$date=null;
            $newrefuel=new Refuel();
            $newrefuel->setCodeCard($refuel["codecard"]);
            $newrefuel->setCodeDriver($refuel["codedriver"]);
            $newrefuel->setVolume($refuel["volume"]);
            $newrefuel->setMileage($refuel["mileage"]);
            $newrefuel->setStationLocation($refuel["stationlocation"]);
            $newrefuel->setDate($date);
            $newrefuel->setProduct($product);
            $newrefuel->setSystem($system);
            $newrefuel->setCreatorUser($user);
            $newrefuel->setCreationDate($date);
            $newrefuel->setHomeagency($homeagency);
            $errors=$validator->validate($newrefuel);
            if (count($errors)==0) {
                $tab_errors = [];
                for ($i = 0; $i < count($errors); $i++) {
                    array_push($tab_errors, $errors->get($i)->getMessage());
                }
                array_push($refuelserrors, [$numLine, $tab_errors]);
            }else $this->getDoctrine()->getManager()->persist($newrefuel);
            $numLine++;
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($refuelserrors);

    }

    /**
     * @Route("/refuel/{id}", name="deleteRefuel", methods={"DELETE"})
     * @ParamConverter(name="refuel", class="App:Refuel")
     * @param Request $request
     * @param Refuel $refuel
     * @return JsonResponse
     */
    public function deleteRefuel(Request $request, Refuel $refuel){
        $this->getDoctrine()->getManager()->remove($refuel);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($refuel);
    }

    /**
     * @Route("/refuel/{id}", name="updateRefuel", methods={"PATCH"})
     * @ParamConverter(name="refuel", class="App:Refuel")
     * @param Request $request
     * @param Refuel $refuel
     * @return JsonResponse
     */
    public function updateRefuel(Request $request,Refuel $refuel){
        $data=json_decode($request->getContent(), true);
        $keys=array_keys($data["refuel"]);
        return new JsonResponse($refuel);
    }

    /**
     * @Route("/refuel/file", name="addFileRefuel", methods={"PUT"})
     */
    public function addFileRefuel(Request $request, FileUploader $fileUploader, FileExtractData $fileExtractData){
        /*Data*/
        $user=$this->getDoctrine->getRepository(User::class)->find($this->getUser());
        $creationdate=new \DateTime("now");
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
        $refuelserrors=$fileExtractData->extractDataFromFile($file, $system, $homeagency, $user, $creationdate);
        return new JsonResponse($refuelserrors);
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
}
