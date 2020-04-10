<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dataTypes=[
            ["name"=>"vl"],
            ["name"=>"porte-conteneur"],
            ["name"=>"porteur"],

        ];

        foreach ($dataTypes as $type){
            $newType= new Type();
            $newType->setName($type["name"]);
            $manager->persist($newType);
        }

        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
