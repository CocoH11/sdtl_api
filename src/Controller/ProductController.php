<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\System;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/api")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="getProduct")
     */
    public function getProduct()
    {
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        $datatosend=[];

        foreach ($products as $product){
            array_push($datatosend, ["id"=>$product->getId(), "name"=>$product->getName()]);
        }
        return new JsonResponse($datatosend);
    }
}
