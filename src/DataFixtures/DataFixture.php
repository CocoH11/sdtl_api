<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Driver;
use App\Entity\Homeagency;
use App\Entity\System;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadHomeagency($manager);
        $this->loadSystem($manager);
        $this->loadType($manager);
        $this->loadActivities($manager);
        $this->loadUser($manager);
        $this->loadDrivers($manager);
    }

    public function loadDrivers(ObjectManager $manager)
    {
        $drivers = [
            ["name" => "Holcvart", "firstname" => "Denis", "homeagency" => 1],
            ["name" => "Grandjean", "firstname" => "Francis", "homeagency" => 1],
            ["name" => "Gaillard", "firstname" => "Renaud", "homeagency" => 2],
            ["name" => "Wostendich", "firstname" => "Pedro", "homeagency" => 2],
        ];

        foreach ($drivers as $driver) {
            $newdriver = new Driver();
            $homeagency = $manager->getRepository(Homeagency::class)->find($driver["homeagency"]);
            $newdriver->setName($driver["name"])
                ->setFirstname($driver["firstname"])
                ->setHomeagency($homeagency);
            $manager->persist($newdriver);
        }
        $manager->flush();
        var_dump("hello");
    }

    public function loadHomeagency(ObjectManager $manager)
    {
        $dataHomeagencies = [
            ["name" => "TC58"],
            ["name" => "TC35"],
            ["name" => "TC59"],
            ["name" => "TC14"],
            ["name" => "TC71"],
            ["name" => "TC63"],
            ["name" => "TC60"],
            ["name" => "TC91"],
            ["name" => "SDTL"],
            ["name" => "ALG"],

        ];

        foreach ($dataHomeagencies as $homeagency) {
            $newHomeagency = new Homeagency();
            $newHomeagency->setName($homeagency["name"]);
            $manager->persist($newHomeagency);
        }
        $manager->flush();
        var_dump("hello");
    }

    public function loadSystem(ObjectManager $manager)
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
        var_dump("hello");
    }

    public function loadType(ObjectManager $manager)
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
        var_dump("hello");
    }

    public function loadUser(ObjectManager $manager)
    {
        $users=[
            ["login"=>"cholcvart", "password"=>"dinosaure0", "roles"=>["ROLE_USER", "ROLE_ADMIN"], "homeagency"=>1],
            ["login"=>"dholcvart", "password"=>"dinosaure1", "roles"=>["ROLE_USER"], "homeagency"=>1],
            ["login"=>"aholcvart", "password"=>"dinosaure2", "roles"=>["ROLE_USER"], "homeagency"=>2]
        ];

        foreach ($users as $user){
            $newuser=new User();
            $homeagency=$manager->getRepository(Homeagency::class)->find($user["homeagency"]);
            $newuser
                ->setLogin($user["login"])
                ->setPassword($this->passwordEncoder->encodePassword($newuser, $user["password"]))
                ->setRoles($user["roles"])
                ->setHomeagency($homeagency);
            $manager->persist($newuser);
        }
        $manager->flush();
        var_dump("hello");
    }

    public function loadActivities(ObjectManager $manager)
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
