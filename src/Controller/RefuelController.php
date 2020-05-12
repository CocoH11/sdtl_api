<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Entity\Refuel;
use App\Entity\Truck;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
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
        $data= json_decode($request->getContent(), true);
        $fileUploader->upload($data["filename"], $data["data"]);
        return new Response(base64_decode($data, false));
    }
}
