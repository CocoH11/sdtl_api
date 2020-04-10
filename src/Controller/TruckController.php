<?php

namespace App\Controller;

use App\Entity\Code;
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

        $newCode= new Code();
        $newCode->setCode("134567YJTTFVGC");
        $newCode->setSystem();

        $newtruck= new Truck();
        $newtruck->setNumberplate(12345);
        $newtruck->addCode();
        $newtruck->setHomeagency(1);
        $newtruck->setType(1);
        $newtruck->setActivity(1);
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
