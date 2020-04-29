<?php

namespace App\Controller;

use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TypeController
 * @package App\Controller
 * @Route("/api")
 */
class TypeController extends AbstractController
{
    /**
     * @Route("/types", name="getTypes", methods={"GET"})
     */
    public function getSystems(Request $request){
        $types=$this->getDoctrine()->getRepository(Type::class)->findAll();
        $datatosend=[];

        foreach ($types as $type){
            array_push($datatosend, ["id"=>$type->getId(), "name"=>$type->getName()]);
        }
        $response=new JsonResponse($datatosend, 200);
        return $response;
    }
}
