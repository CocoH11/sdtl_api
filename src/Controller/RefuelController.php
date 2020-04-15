<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Entity\Refuel;
use App\Entity\Truck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RefuelController extends AbstractController
{
    /**
     * @Route("/refuel", name="addRefuel", methods={"PUT"})
     */
    public function addRefuel(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);
        //Doctrine
        $doctrine=$this->getDoctrine();
        $driver=$doctrine->getRepository(Driver::class)->find($data["refuel"]["driver"]);
        $truck=$doctrine->getRepository(Truck::class)->find($data["refuel"]["truck"]);

        //Refuel
        $newRefuel= new Refuel();
        $newRefuel
            ->setVolume($data["refuel"]["volume"])
            ->setTruck($truck)
            ->setDriver($driver);

        $errors=$validator->validate($newRefuel);
        $errorsmessages=[];
        if (count($errors)>0){
            foreach ($errors as $error){
                array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);
            }
        }if ($data["refuel"]["driver"] && !$driver){
            array_push($errorsmessages, ["driver"=>"le véhicule entré est invalide"]);
        }
        else{
            $doctrine->getManager()->persist($newRefuel);
            $doctrine->getManager()->flush();
        }
        return new JsonResponse($errorsmessages, 200);
    }



    /**
     * @Route("/refuels", name="addRefuels", methods={"PUT"})
     */
    public function addRefuels(Request $request){
        $data=json_decode($request->getContent(), true);
        //Doctrine
        $doctrine=$this->getDoctrine();

        foreach ($data["refuels"] as $refuel){
            if ($refuel["driver"]){
                $driver=$doctrine->getRepository(Driver::class)->find($refuel["driver"]);
            }else $driver=null;
            $truck=$doctrine->getRepository(Truck::class)->find($refuel["truck"]);
            $newRefuel= new Refuel();
            $newRefuel
                ->setVolume($refuel["volume"])
                ->setTruck($truck);

            if ($driver){
                $newRefuel->setDriver($driver);
            }
            ;
            $doctrine->getManager()->persist($newRefuel);
        }
        $doctrine->getManager()->flush();
        return new Response("", 200);
    }

    /**
     * @Route("/refuel", name="deleteRefuel", methods={"DELETE"})
     */
    public function deleteRefuel(Request $request){

    }

    /**
     * @Route("/refuel/{id}", name="updateRefuel", methods={"PATCH"})
     */
    public function updateRefuel(Request $request,int $id){
        $data=json_decode($request->getContent(), true);
        //Doctrine
        $doctrine=$this->getDoctrine();
        $refuel=$doctrine->getRepository(Refuel::class)->find($id);
        if ($data["refuel"]["volume"])$refuel->setVolume($data["refuel"]["volume"]);
        if ($data["refuel"]["truck"]){
            $truck=$doctrine->getRepository(Truck::class)->find($data["refuel"]["truck"]);
            $refuel->setTruck($truck);
        }
        if ($data["refuel"]["driver"]) {
            var_dump($data["refuel"]["driver"]);
            $driver = $doctrine->getRepository(Driver::class)->find($data["refuel"]["driver"]);
            $refuel->setDriver($driver);
        }else{
            $refuel->setDriver(null);
        }
        $doctrine->getManager()->flush();
        return new Response("", 200);
    }
}
