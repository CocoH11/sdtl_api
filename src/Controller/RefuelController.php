<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Entity\Refuel;
use App\Entity\Truck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefuelController extends AbstractController
{
    /**
     * @Route("/refuel", name="addRefuel", methods={"PUT"})
     */
    public function addRefuel(Request $request){
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
        ;
        if ($driver){
            $newRefuel->setDriver($driver);
        }
        $doctrine->getManager()->persist($newRefuel);
        $doctrine->getManager()->flush();
        return new Response("", 200);
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
     * @Route("/refuel", name="updateRefuel", methods={"PATCH"})
     */
    public function updateRefuel(Request $request,int $id){

    }

}
