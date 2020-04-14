<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Code;
use App\Entity\Homeagency;
use App\Entity\System;
use App\Entity\Truck;
use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="addTruck", methods={"PUT"})
     */
    public function addTruck(Request $request){
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
        foreach ($data["codes"] as $code){
            $system=$doctrine->getRepository(System::class)->find($code["system"]);
            $newCode= new Code();
            $newCode
                ->setCode($code["code"])
                ->setSystem($system)
            ;
            $newtruck->addCode($newCode);
            $doctrine->getManager()->persist($newCode);
        }
        $doctrine->getManager()->persist($newtruck);
        $doctrine->getManager()->flush();
        return new Response("", 200);

    }


    /**
     * @Route("truck/{id}", name="deleteTruck", methods={"DELETE"})
     */
    public function deleteTruck(Request $request,int $id){
        //Doctrine
        $doctrine=$this->getDoctrine();
        $truck=$doctrine->getRepository(Truck::class)->find($id);
        //codes of the truck
        foreach ($truck->getCodes() as $code){
            $doctrine->getManager()->remove($code);
        }
        //truck
        $doctrine->getManager()->remove($truck);
        $doctrine->getManager()->flush();
        return new Response($id, 200);
    }

    /**
     * @Route("truck/{id}", name="updateTruck", methods={"PATCH"})
     */
    public function updateTruck(Request $request,int $id){
        //Doctrine
        $doctrine=$this->getDoctrine();
        $data=json_decode($request->getContent(), true);
        $truck=$doctrine->getRepository(Truck::class)->find($id);


        return new Response("updateTruck", 200);
    }




}
