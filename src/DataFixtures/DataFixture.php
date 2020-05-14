<?php

namespace App\DataFixtures;


use App\Entity\Homeagency;
use App\Entity\System;
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
        $this->loadUser($manager);
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
            ["name"=>"uta"],
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
}
