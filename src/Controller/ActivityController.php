<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActivityController
 * @package App\Controller
 * @Route("/api")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/activities", name="getActivities", methods={"GET"})
     */
    public function getSystems(Request $request){
        $activities=$this->getDoctrine()->getRepository(Activity::class)->findAll();
        $datatosend=[];

        foreach ($activities as $activity){
            array_push($datatosend, ["id"=>$activity->getId(), "name"=>$activity->getName()]);
        }
        $response=new JsonResponse($datatosend, 200);
        return $response;
    }
}
