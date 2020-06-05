<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DataController
 * @package App\Controller
 * @Route("/api")
 */
class DataController extends AbstractController
{
    /**
     * @Route("/data/{data}", name="getData", defaults={"data": "all"}, requirements={"data": "^all|system|product$"},  methods={"GET"})
     * @param string $data
     * @return JsonResponse
     */
    public function getData(string $data): JsonResponse
    {
        $data_tab=null;
        $user=$this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        switch ($data){
            case "all":
                $data_tab=["systems"=>$this->getSystems($user), "products"=>$this->getProducts()];
                break;
            case "system":
                $data_tab=["systems"=>$this->getSystems($user)];
                break;
            case "product":
                $data_tab=["products"=>$this->getProducts()];
                break;
        }
        return new JsonResponse($data_tab);
    }

    public function getSystems(User $user)
    {
        $systems_tab=[];
        $systems=$user->getHomeagency()->getSystems();
        foreach ($systems as $system){
            array_push($systems_tab, ["id"=>$system->getId(), "name"=>$system->getName()]);
        }
        return $systems_tab;
    }

    public function getProducts()
    {
        $products_tab=[];
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        foreach ($products as $product){
            array_push($products_tab, ["id"=>$product->getId(), "name"=>$product->getName()]);
        }
        return $products_tab;
    }
}
