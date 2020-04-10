<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DriverController extends AbstractController
{
    /**
     * @Route("/driver", name="driver")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DriverController.php',
        ]);
    }

    /**
     * @Route("/driver", name="addDriver", methods={"PUT"})
     */
    public function addDriver(Request $request){

    }

    /**
     * @Route("/driver", name="deleteDriver", methods={"DELETE"})
     */
    public function deleteDriver(Request $request){

    }

    /**
     * @Route("/driver/{id}", name="updateDriver", methods={"PATCH"})
     */
    public function updateDriver(Request $request, int $id){

    }
}
