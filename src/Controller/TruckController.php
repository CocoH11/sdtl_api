<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="truck")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TruckController.php',
        ]);
    }

    /**
     * @Route("/truck", name="addTruck", methods={"PUT"})
     */
    public function addTruck(Request $request){

    }

    /**
     * @Route("truck", name="deleteTruck", methods={"DELETE"})
     */
    public function deleteTruck(Request $request){

    }

    /**
     * @Route("truck", name="updateTruck", methods={"PATCH"})
     */
    public function updateTruck(Request $request){

    }


}
