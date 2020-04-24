<?php

namespace App\Controller;

use App\Entity\System;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SystemController
 * @package App\Controller
 * @Route("/api")
 */
class SystemController extends AbstractController
{
    /**
     * @Route("/systems", name="getSystems", methods={"GET"})
     */
    public function getSystems(Request $request){
        $systems=$this->getDoctrine()->getRepository(System::class)->findAll();
        $datatosend=[];

        foreach ($systems as $system){
            array_push($datatosend, ["id"=>$system->getId(), "name"=>$system->getName()]);
        }
        $response=new JsonResponse($datatosend, 200);
        return $response;
    }
}
