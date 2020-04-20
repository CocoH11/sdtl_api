<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Code;
use App\Entity\Homeagency;
use App\Entity\System;
use App\Entity\Truck;
use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TruckController
 * @package App\Controller
 * @Route("/api")
 */
class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="addTruck", methods={"PUT"})
     */
    public function addTruck(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);
        //Doctrine
        $doctrine=$this->getDoctrine();
        $type=$doctrine->getRepository(Type::class)->find($data["truck"]["type"]);
        $homeagency=$doctrine->getRepository(Homeagency::class)->find($data["truck"]["homeagency"]);
        $activity=$doctrine->getRepository(Activity::class)->find($data["truck"]["activity"]);
        //Truck
        $newtruck= new Truck();
        $newtruck
            ->setNumberplate($data["truck"]["numberplate"])
            ->setHomeagency($homeagency)
            ->setType($type)
            ->setActivity($activity)
        ;
        //Codes
        foreach ($data["truck"]["codes"] as $code){
            $system=$doctrine->getRepository(System::class)->find($code["system"]);
            $newCode= new Code();
            $newCode
                ->setCode($code["code"])
                ->setSystem($system)
            ;
            $newtruck->addCode($newCode);
            //$doctrine->getManager()->persist($newCode);
        }
        $errorsmessages=[];
        $errors=$validator->validate($newtruck);
        if (count($errors)>0){
            foreach ($errors as $error){
                array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);

            }
        }else{
            $doctrine->getManager()->persist($newtruck);
            $doctrine->getManager()->flush();
        }
        return new JsonResponse($errorsmessages, 200);

    }

    /**
     * @Route("/trucks", name="addTrucks", methods={"PUT"})
     */
    public function addTrucks(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);
        //var_dump($data);
        //Doctrine
        $doctrine=$this->getDoctrine();
        $truckserrorsmessages=[];
        foreach ($data["trucks"] as $truck){
            $type=$doctrine->getRepository(Type::class)->find($truck["type"]);
            $homeagency=$doctrine->getRepository(Homeagency::class)->find($truck["homeagency"]);
            $activity=$doctrine->getRepository(Activity::class)->find($truck["activity"]);

            $newtruck= new Truck();
            $newtruck
                ->setNumberplate($truck["numberplate"])
                ->setHomeagency($homeagency)
                ->setType($type)
                ->setActivity($activity)
            ;
            //Codes
            foreach ($truck["codes"] as $code){
                $system=$doctrine->getRepository(System::class)->find($code["system"]);
                $newCode= new Code();
                $newCode
                    ->setCode($code["code"])
                    ->setSystem($system)
                ;
                $newtruck->addCode($newCode);
            }
            $errorsmessages=[];
            $errors=$validator->validate($newtruck);
            if (count($errors)>0){
                foreach ($errors as $error){
                    array_push($errorsmessages, [$error->getPropertyPath(), $error->getMessage()]);
                }
            }else $doctrine->getManager()->persist($newtruck);
            array_push($truckserrorsmessages, $errorsmessages);
        }
        $doctrine->getManager()->flush();
        return new JsonResponse($truckserrorsmessages, 200);
    }


    /**
     * @Route("truck/{id}", name="deleteTruck", methods={"DELETE"})
     */
    public function deleteTruck(Request $request,int $id){
        //Doctrine
        $doctrine=$this->getDoctrine();
        $truck=$doctrine->getRepository(Truck::class)->find($id);
        $error=null;
        if ($truck){
        //truck
            $doctrine->getManager()->remove($truck);
            $doctrine->getManager()->flush();
        }else $error="Le véhicule à supprimer n'existe pas";
        return new JsonResponse($error, 200);
    }

    /**
     * @Route("truck/{id}", name="updateTruck", methods={"PATCH"})
     */
    public function updateTruck(Request $request,int $id){
        //Doctrine
        $doctrine=$this->getDoctrine();
        $truck=$doctrine->getRepository(Truck::class)->find($id);
        $error=null;
        if ($truck) {
            $data = json_decode($request->getContent(), true);
            if ($data["truck"]["numberplate"]) $truck->setNumberplate($data["truck"]["numberplate"]);
            if ($data["truck"]["homeagency"]) {
                $homeagency = $this->getDoctrine()->getRepository(Homeagency::class)->find($data["truck"]["homeagency"]);
                $truck->setHomeagency($homeagency);
            }
            if ($data["truck"]["type"]) {
                $type = $doctrine->getRepository(Type::class)->find($data["truck"]["type"]);
                $truck->setType($type);
            }
            if ($data["truck"]["activity"]) {
                $activity = $doctrine->getRepository(Activity::class)->find($data["truck"]["activity"]);
                $truck->setActivity($activity);
            }
            if ($data["truck"]["codes"]) {
                foreach ($data["truck"]["codes"] as $code) {
                    $system = $doctrine->getRepository(System::class)->find($code["system"]);
                    $newcode = new Code();
                    $newcode->setCode($code["code"]);
                    $newcode->setSystem($system);
                    $truck->addCode($newcode);
                }
            }
            $doctrine->getManager()->flush();
        }else $error="Le véhicule à modifier n'existe pas";
        return new JsonResponse($error, 200);
    }
}
