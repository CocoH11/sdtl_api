<?php

namespace App\Controller;

use App\Entity\Truck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TruckController extends AbstractController
{
    /*/**
     * @Route("/truck", name="truck")
     */
    /*public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TruckController.php',
        ]);
    }
*/
    /**
     * @Route("/truck", name="addTruck", methods={"POST"})
     */
    public function addTruck(Request $request){

        $newtruck= new Truck();
        $newtruck->setNumberplate();
        $newtruck->addCode();
        $newtruck->setHomeagency();
        $newtruck->setType();
        $newtruck->setActivity();
        return new Response("addTruck", 200);

    }

    /**
     * @Route("truck", name="deleteTruck", methods={"DELETE"})
     */
    public function deleteTruck(Request $request){
        return new Response("deleteTruck", 200);
    }

    /**
     * @Route("truck", name="updateTruck", methods={"PATCH"})
     */
    public function updateTruck(Request $request){
        return new Response("updateTruck", 200);
    }


}
