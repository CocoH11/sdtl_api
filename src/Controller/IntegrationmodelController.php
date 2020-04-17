<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationmodelController extends AbstractController
{
    /**
     * @Route("/integrationmodel", name="addIntegrationmodel", methods={"PUT"})
     */
    public function addIntegrationmodel(Request $request){
        return new JsonResponse("", 200);

    }

    /**
     * @Route("/integrationmodels", name="addIntegrationmodels", methods={"PUT"}
     */
    public function addIntegrationmodels(Request $request){
        return new JsonResponse("", 200);
    }

    /**
     * @Route("/integrationmodel/{id}", name="deleteIntegrationmodel", methods={"DELETE"})
     */
    public function deleteIntegrationmodel(Request $request, int $id){
        return new JsonResponse("", 200);
    }

    /**
     * @Route("/integrationmodel/{id}", name="updateIntegrationmodel", methods={"PATCH"})
     */
    public function updateIntegrationmodel($id){
        return new JsonResponse("", 200);
    }
}
