<?php

namespace App\Controller;

use App\Entity\Homeagency;
use App\Entity\Product;
use App\Entity\Refuel;
use App\Entity\User;
use App\Entity\System;
use App\Service\FileExtractData;
use App\Service\FileUploader;
use DateTime;
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
    private string $refuel_refuel_name;
    private string $refuel_refuels_name;
    private string $refuel_volume_name;
    private string $refuel_codecard_name;
    private string $refuel_codedriver_name;
    private string $refuel_system_name;
    private string $refuel_date_name;
    private string $refuel_stationlocation_name;
    private string $refuel_mileage_name;
    private string $refuel_product_name;
    private int $limit;

    public function __construct(string $refuel_refuel_name, string $refuel_refuels_name, string $refuel_volume_name, string $refuel_codecard_name, string $refuel_codedriver_name, string $refuel_system_name, string $refuel_date_name, string $refuel_stationlocation_name, string $refuel_mileage_name, string $refuel_product_name, int $limit)
    {
        $this->refuel_codecard_name=$refuel_codecard_name;
        $this->refuel_codedriver_name=$refuel_codedriver_name;
        $this->refuel_date_name=$refuel_date_name;
        $this->refuel_mileage_name=$refuel_mileage_name;
        $this->refuel_product_name=$refuel_product_name;
        $this->refuel_stationlocation_name=$refuel_stationlocation_name;
        $this->refuel_volume_name=$refuel_volume_name;
        $this->refuel_system_name=$refuel_system_name;
        $this->refuel_refuels_name=$refuel_refuels_name;
        $this->refuel_refuel_name=$refuel_refuel_name;
        $this->limit=$limit;
    }

    /**
     * @Route("/nbrefuels", name="getNbRefuels", methods={"GET"})
     */
    public function getNbRefuels(): JsonResponse
    {
        return new JsonResponse(intval($this->getDoctrine()->getRepository(Refuel::class)->getNbRefuels()));
    }

    /**
     * @Route("/refuels/{page<\d+>?1}", name="getRefuels", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getRefuels(Request $request): JsonResponse
    {
        $datatosend=[];
        //Homeagency
        $homeagency=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getHomeagency();

        //Refuel
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }
        $refuels=$this->getDoctrine()->getRepository(Refuel::class)->findAllRefuelsByHomeagency($page, $this->limit, $homeagency->getId());
        $refuels_tab=[];
        foreach ($refuels as $refuel){
            array_push($refuels_tab, ["id"=>$refuel->getId(), "volume"=>$refuel->getVolume(), "codecard"=>$refuel->getCodeCard(), "codedriver"=>$refuel->getCodeDriver(), "system"=>$refuel->getSystem()->getId(), "stationlocation"=>$refuel->getStationLocation(), "product"=>$refuel->getProduct()->getId(), "mileage"=>$refuel->getMileage(), "date"=>$refuel->getDate()]);
        }

        //System
        $systems=$homeagency->getSystems();
        $systems_tab=[];
        foreach($systems as $system){
            array_push($systems_tab, ["id"=>$system->getId(), "name"=>$system->getName()]);
        }

        //Product
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        $products_tab=[];
        foreach ($products as $product){
            array_push($products_tab, ["id"=>$product->getId(), "name"=>$product->getName()]);
        }
        array_push($datatosend, $refuels_tab);
        array_push($datatosend, $systems_tab);
        array_push($datatosend, $products_tab);
        return new JsonResponse($datatosend);
    }

    /**
     * @Route("/refuels", name="addRefuels", methods={"PUT"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FileExtractData $fileExtractData
     * @return JsonResponse
     */
    public function addRefuels(Request $request, ValidatorInterface $validator, FileExtractData $fileExtractData): JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $homeagency=$this->checkHomeAgency();
        $user= $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $refuelserrors=[];
        $numLine=0;
        foreach ($data[$this->refuel_refuels_name] as $refuel){
            $system=$this->getDoctrine()->getRepository(System::class)->find($refuel[$this->refuel_system_name]);
            $product=$this->getDoctrine()->getRepository(Product::class)->find($refuel[$this->refuel_product_name]);
            $date=\DateTime::createFromFormat("Y-m-dH:m:s", $refuel[$this->refuel_date_name]);
            $creationDate=new \DateTime("now");
            if (!$date)$date=null;
            $newrefuel= $this->createRefuel($refuel[$this->refuel_stationlocation_name], $date, $refuel[$this->refuel_codecard_name], $refuel[$this->refuel_codedriver_name], $refuel[$this->refuel_volume_name], $product, $refuel[$this->refuel_mileage_name], $system, $homeagency, $user, $creationDate);
            $errors=$validator->validate($newrefuel);
            if (count($errors)>0)array_push($refuelserrors, $fileExtractData->buildErrorsTab($errors, $numLine));
            else $this->getDoctrine()->getManager()->persist($newrefuel);
            $numLine++;
        }
        $this->getDoctrine()->getManager()->flush();
        if (count($refuelserrors)==0)$message="Tout s'est bien passé";
        else $message="Il y a des erreurs dans le fichier";
        return new JsonResponse([$message, $refuelserrors]);

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
        return new JsonResponse("hello");
    }

    /**
     * @Route("/refuel/{id}", name="updateRefuel", methods={"PUT"})
     * @ParamConverter(name="refuel", class="App:Refuel")
     * @param Request $request
     * @param Refuel $refuel
     * @param ValidatorInterface $validator
     * @param FileExtractData $fileExtractData
     * @return JsonResponse
     */
    public function updateRefuel(Request $request,Refuel $refuel, ValidatorInterface $validator, FileExtractData $fileExtractData){
        $refueldata=json_decode($request->getContent(), true)[$this->refuel_refuel_name];
        if (isset($refueldata[$this->refuel_volume_name]))$refuel->setVolume($refueldata[$this->refuel_volume_name]);
        if (isset($refueldata[$this->refuel_codecard_name]))$refuel->setCodeCard($refueldata[$this->refuel_codecard_name]);
        if (isset($refueldata[$this->refuel_codedriver_name]))$refuel->setCodeDriver($refueldata[$this->refuel_codedriver_name]);
        if (isset($refueldata[$this->refuel_stationlocation_name]))$refuel->setStationLocation($refueldata[$this->refuel_stationlocation_name]);
        if (isset($refueldata[$this->refuel_mileage_name]))$refuel->setMileage($refueldata[$this->refuel_mileage_name]);
        if (isset($refueldata[$this->refuel_product_name])){
            $product=$this->getDoctrine()->getRepository(Product::class)->find($refueldata[$this->refuel_product_name]);
            $refuel->setProduct($product);
        }
        if (isset($refueldata[$this->refuel_system_name])){
            $system=$this->getDoctrine()->getRepository(System::class)->find($refueldata[$this->refuel_system_name]);
            $refuel->setSystem($system);
        }
        if (isset($refueldata[$this->refuel_date_name])){
            $date=\DateTime::createFromFormat("Y-m-dH:m:s", $refueldata[$this->refuel_date_name]);
            $refuel->setDate($date);
        }
        $modificationdate=new \DateTime("now");
        $modifieruser=$this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $refuel->setModifierUser($modifieruser);
        $refuel->setModificationDate($modificationdate);
        $errors=$validator->validate($refuel);
        $tab_errors = [];
        if (count($errors)>0)$tab_errors=$fileExtractData->buildErrorsTab($errors, 1);
        else {
            $this->getDoctrine()->getManager()->persist($refuel);
            $this->getDoctrine()->getManager()->flush();
        }
        return new JsonResponse($tab_errors);
    }

    /**
     * @Route("/refuel/file", name="addFileRefuel", methods={"PUT"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param FileExtractData $fileExtractData
     * @return JsonResponse
     */
    public function addFileRefuel(Request $request, FileUploader $fileUploader, FileExtractData $fileExtractData){
        /*Data*/
        $user=$this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $creationdate=new \DateTime("now");
        $data= json_decode($request->getContent(), true);
        $numSystem=$data[$this->refuel_system_name];
        $filedata=base64_decode($data["data"]);
        $fileExtension=$data["fileExtension"];
        /*Check the System and the HomeAgency*/
        $homeagency=$this->checkHomeAgency();
        $system=$this->checkSystem($numSystem);
        /*Save File*/
        $newFileName=$fileUploader->upload($homeagency, $system, $filedata, $fileExtension);
        /*Extract Data*/
        $file=new File($newFileName);
        $dataextracted=$fileExtractData->extractDataFromFile($file, $system, $homeagency, $user, $creationdate);

        if (count($dataextracted)==0)$message="Tout s'est bien passé";
        else $message="Il y a des erreurs dans le fichier";
        return new JsonResponse([$message, $dataextracted]);
    }

    public function checkSystem(int $sysValue){
        return $this->getDoctrine()->getRepository(System::class)->find($sysValue);
    }

    public function checkHomeAgency(){
        $user= $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        return $user->getHomeAgency();
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

    public function createRefuel(string $stationlocation, DateTime $date, string $codecard, string $codedriver, float $volume, Product $product, int $mileage, System $system, Homeagency $homeagency, User $user, DateTime $creationdate): Refuel{
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
            ->setCreatorUser($user)
            ->setCreationDate($creationdate)
            ->setSystem($system);
        return $newrefuel;
    }
}
