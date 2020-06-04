<?php

namespace App\Controller;

use App\Entity\System;
use App\Entity\User;
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
        $homeagency=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getHomeagency();
        $systems=$homeagency->getSystems();
        $datatosend=[];

        foreach ($systems as $system){
            array_push($datatosend, ["id"=>$system->getId(), "name"=>$system->getName()]);
        }
        return new JsonResponse($datatosend, 200);
    }
}
