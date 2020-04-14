<?php

namespace App\Controller;

use App\Entity\Driver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            //$errorsString = (string) $errors;

            return new Response("c'est pas bien");
        }
        $doctrine->getManager()->persist($newdriver);
        $doctrine->getManager()->flush();
        return new Response("", 200);
    }

    /**
     * @Route("/drivers", name="addDrivers", methods={"PUT"})
     */
    public function addDrivers(Request $request){
        $data=json_decode($request->getContent(), true);

        //Doctrine
        $doctrine=$this->getDoctrine();

        foreach ($data["drivers"] as $driver){
            $newdriver=new Driver();
            $newdriver
                ->setName($driver["name"])
                ->setFirstname($driver["firstname"])
            ;
            $doctrine->getManager()->persist($newdriver);
        }
        $doctrine->getManager()->flush();
        return new Response("", 200);
    }

    /**
     * @Route("/driver/{id}", name="deleteDriver", methods={"DELETE"})
     */
    public function deleteDriver(Request $request, $id){
        //Doctrine
        $doctrine=$this->getDoctrine();

        //Driver
        $driver=$doctrine->getRepository(Driver::class)->find($id);
        $doctrine->getManager()->remove($driver);
        $doctrine->getManager()->flush();
        return new Response($id, 200);
    }

    /**
     * @Route("/driver/{id}", name="updateDriver", methods={"PATCH"})
     */
    public function updateDriver(Request $request, int $id){


    }
}
