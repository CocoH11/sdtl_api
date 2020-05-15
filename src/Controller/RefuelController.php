<?php

namespace App\Controller;

use App\Entity\Homeagency;
use App\Entity\User;
use App\Entity\System;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RefuelController
 * @package App\Controller
 * @Route("/api")
 */
class RefuelController extends AbstractController
{
    /**
     * @Route("/refuel", name="addRefuel", methods={"PUT"})
     */
    public function addRefuel(Request $request, ValidatorInterface $validator){
    }
    /**
     * @Route("/refuels", name="addRefuels", methods={"PUT"})
     */
    public function addRefuels(Request $request, ValidatorInterface $validator){
    }

    /**
     * @Route("/refuel/{id}", name="deleteRefuel", methods={"DELETE"})
     */
    public function deleteRefuel(Request $request, int $id){
    }

    /**
     * @Route("/refuel/{id}", name="updateRefuel", methods={"PATCH"})
     */
    public function updateRefuel(Request $request,int $id){}

    /**
     * @Route("/refuel/file", name="addFileRefuel", methods={"PUT"})
     */
    public function addFileRefuel(Request $request, FileUploader $fileUploader){
        /*Data*/
        $data= json_decode($request->getContent(), true);
        $numSystem=$data["system"];
        $filedata=base64_decode($data["data"]);
        $fileExtension=$data["fileExtension"];
        /*Choose Directory*/
        $homeagency=$this->checkHomeAgency();
        $system=$this->checkSystem($numSystem);

        /*Save File*/
        $newFileName=$fileUploader->upload($homeagency, $system, $filedata, $fileExtension);
        //$file=new File($newFileName);
        return new JsonResponse($this->getUser()->getRoles());
    }

    public function checkSystem(int $sysValue){
        $system=$this->getDoctrine()->getRepository(System::class)->find($sysValue);
        return $system;
    }

    public function checkHomeAgency(){
        $user= $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $homeagency=$user->getHomeAgency();
        return $homeagency;
    }
    /**
     * @Route("/refuel", name="testSecureLogin", methods={"POST"})
     */
    public function testSecureLogin(){
        return new Response("testSecureLogin");
    }
}
