<?php

namespace App\DataFixtures;

use App\Entity\System;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SystemFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $datasystems=[
            ["name"=>"ids"],
            ["name"=>"as24"],
            ["name"=>"dkv"],
            ["name"=>"lafont"],
            ["name"=>"tokheim"]
        ];

        foreach ($datasystems as $system){
            $newsystem= new System();
            $newsystem->setName($system["name"]);
            $manager->persist($newsystem);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
