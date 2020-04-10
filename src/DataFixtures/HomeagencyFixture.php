<?php

namespace App\DataFixtures;

use App\Entity\Homeagency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HomeagencyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dataHomeagencies=[
            ["name"=>"TC58"],
            ["name"=>"TC35"],
            ["name"=>"TC59"],
            ["name"=>"TC14"],
            ["name"=>"TC71"],
            ["name"=>"TC63"],
            ["name"=>"TC60"],
            ["name"=>"TC91"],
            ["name"=>"SDTL"],
            ["name"=>"ALG"],

        ];

        foreach ($dataHomeagencies as $homeagency){
            $newHomeagency=new Homeagency();
            $newHomeagency->setName($homeagency["name"]);
            $manager->persist($newHomeagency);
        }

        $manager->flush();
    }
}
