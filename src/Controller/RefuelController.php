<?php

namespace App\Controller;

use http\Message\Body;
use PhpParser\JsonDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefuelController extends AbstractController
{
    /**
     * @Route("/refuel", name="refuel", methods={"GET"})
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RefuelController.php',
        ]);
    }

    /**
     * @Route("/refuel/1", name="addRefuel", methods={"PUT"})
     */
    public function addRefuel(Request $request){
        $refueldata=$request->getContent();
        //$refueldata=$request->request->all();
        if ($refueldata){
            return new Response($refueldata, 200);
        }else{
            return new Response("ca marche pas", 500);
        }
        //return new Response("hello");
    }

    /**
     * @Route("/refuel/n", name="addRefuels", methods={"PUT"})
     */
    public function addRefuels(Request $request){
        $refueldata=$request->getContent();
        if ($refueldata){
            return new Response("ca marche", 200);
        }else{
            return new Response("ca marche pas", 500);
        }
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
