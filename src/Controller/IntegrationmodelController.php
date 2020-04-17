<?php

namespace App\Controller;

use App\Entity\Homeagency;
use App\Entity\Integrationmodel;
use App\Entity\System;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IntegrationmodelController extends AbstractController
{
    /**
     * @Route("/integrationmodel", name="addIntegrationmodel", methods={"PUT"})
     */
    public function addIntegrationmodel(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);
        $doctrine=$this->getDoctrine();
        $system=$doctrine->getRepository(System::class)->find($data["integrationmodel"]["system"]);
        $homeagency=$doctrine->getRepository(Homeagency::class)->find($data["integrationmodel"]["homeagency"]);
        $newintegrationmodel= new Integrationmodel();
        $newintegrationmodel
            ->setCodedriverlocation($data["integrationmodel"]["codedriverlocation"])
            ->setCodetrucklocation($data["integrationmodel"]["codetrucklocation"])
            ->setDatelocation($data["integrationmodel"]["datelocation"])
            ->setDateformat($data["integrationmodel"]["dateformat"])
            ->setVolumelocation($data["integrationmodel"]["volumelocation"])
            ->setMileagetrucklocation($data["integrationmodel"]["mileagetrucklocation"])
            ->setSystem($system)
            ->setHomeagency($homeagency)
        ;
        $errors=$validator->validate($newintegrationmodel);
        $errorsmessages=[];
        if (count($errors)>0){
            foreach ($errors as $error){
                array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);
            }
        }else{
            $doctrine->getManager()->persist($newintegrationmodel);
            $doctrine->getManager()->flush();
        }
        return new JsonResponse($errorsmessages, 200);
    }

    /**
     * @Route("/integrationmodels", name="addIntegrationmodels", methods={"PUT"})
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
