<?php

namespace App\Controller;

use App\Entity\Driver;
use PhpParser\Node\Expr\List_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DriverController
 * @package App\Controller
 * @Route("/api")
 */
class DriverController extends AbstractController
{
    /**
     * @Route("/driver", name="addDriver", methods={"PUT"})
     */
    public function addDriver(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);
        //Doctrine
        $doctrine=$this->getDoctrine();

        //Driver
        $newdriver= new Driver();
        $newdriver
            ->setName($data["driver"]["name"])
            ->setFirstname($data["driver"]["firstname"])
        ;
        $errors = $validator->validate($newdriver);
        $errorsmessages=[];
        if (count($errors) > 0) {

            foreach ($errors as $error){
                array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);
            }
        }else{
            $doctrine->getManager()->persist($newdriver);
            $doctrine->getManager()->flush();
        }
        return new JsonResponse($errorsmessages, 200);
    }

    /**
     * @Route("/drivers", name="addDrivers", methods={"PUT"})
     */
    public function addDrivers(Request $request, ValidatorInterface $validator){
        $data=json_decode($request->getContent(), true);

        //Doctrine
        $doctrine=$this->getDoctrine();
        $driverserrorsmessages=[];
        foreach ($data["drivers"] as $driver){
            $errorsmessages=[];
            $newdriver=new Driver();
            $newdriver
                ->setName($driver["name"])
                ->setFirstname($driver["firstname"])
            ;
            $errors = $validator->validate($newdriver);
            if (count($errors)>0){
                foreach ($errors as $error){
                    array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);
                }
            }else{
                $doctrine->getManager()->persist($newdriver);
            }
            array_push($driverserrorsmessages, $errorsmessages);
        }
        $doctrine->getManager()->flush();
        return new JsonResponse($driverserrorsmessages, 200);
    }

    /**
     * @Route("/driver/{id}", name="deleteDriver", methods={"DELETE"})
     */
    public function deleteDriver(Request $request, $id){
        //Doctrine
        $doctrine=$this->getDoctrine();
        //Driver
        $driver=$doctrine->getRepository(Driver::class)->find($id);
        $error=null;
        if ($driver) {
            $doctrine->getManager()->remove($driver);
            $doctrine->getManager()->flush();
        }else $error="Le chauffeur à supprimer n'existe dans la base de données";
        return new Response($error, 200);
    }

    /**
     * @Route("/driver/{id}", name="updateDriver", methods={"PATCH"})
     */
    public function updateDriver(Request $request, int $id){


    }
}
