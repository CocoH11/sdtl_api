<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dataActivities=[
            ["name"=>"Méssagerie"],
            ["name"=>"Nationale"],
            ["name"=>"Régionale"]
        ];

        foreach ($dataActivities as $activity){
            $newactivity= new Activity();
            $newactivity->setName($activity["name"]);
            $manager->persist($newactivity);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
