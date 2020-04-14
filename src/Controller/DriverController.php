<?php

namespace App\Controller;

use App\Entity\Driver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DriverController extends AbstractController
{
    /**
     * @Route("/driver", name="addDriver", methods={"PUT"})
     */
    public function addDriver(Request $request){
        $data=json_decode($request->getContent(), true);
        //Doctrine
        $doctrine=$this->getDoctrine();

        //Driver
        $newdriver= new Driver();
        $newdriver
            ->setName($data["driver"]["name"])
            ->setFirstname($data["driver"]["firstname"])
        ;
        $doctrine->getManager()->persist($newdriver);
        $doctrine->getManager()->flush();
        return new Response("", 200);
    }

    /**
     * @Route("/driver/{id}", name="deleteDriver", methods={"DELETE"})
     */
    public function deleteDriver(Request $request, $id){
        //Doctrine
        $doctrine=$this->getDoctrine();

        //Driver
        $driver=$doctrine->getRepository(Driver::class)->find($id);
        $doctrine->getManager()->remove($driver);
        $doctrine->getManager()->flush();
        return new Response($id, 200);
    }

    /**
     * @Route("/driver/{id}", name="updateDriver", methods={"PATCH"})
     */
    public function updateDriver(Request $request, int $id){


    }
}
