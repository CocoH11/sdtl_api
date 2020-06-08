<?php

namespace App\DataFixtures;


use App\Entity\Homeagency;
use App\Entity\Product;
use App\Entity\System;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataFixture extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private string $targetDirectory;
    private Filesystem $filesystem;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, $targetDirectory){
        $this->passwordEncoder = $passwordEncoder;
        $this->targetDirectory=$targetDirectory;
        $this->filesystem=new Filesystem();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadProduct($manager);
        $this->loadSystem($manager);
        $this->loadHomeagency($manager);
        $this->loadUser($manager);
    }

    public function loadHomeagency(ObjectManager $manager)
    {
        $dataHomeagencies = [
            ["name" => "TC58", "directoryname"=>"TC58/", "systems"=>[4,5]],
            ["name" => "TC35", "directoryname"=>"TC35/", "systems"=>[1,5]],
            ["name" => "TC59", "directoryname"=>"TC59/", "systems"=>[4,5]],
            ["name" => "TC14", "directoryname"=>"TC14/", "systems"=>[2,3]],
            ["name" => "TC71", "directoryname"=>"TC71/", "systems"=>[2,3,5]],
            ["name" => "TC63", "directoryname"=>"TC63/", "systems"=>[4,5]],
            ["name" => "TC60", "directoryname"=>"TC60/", "systems"=>[2,5]],
            ["name" => "TC91", "directoryname"=>"TC91/", "systems"=>[2,5]],
            ["name" => "TC70", "directoryname"=>"TC70/", "systems"=>[2,3,6]],
            ["name" => "ALG", "directoryname"=>"ALG/", "systems"=>[5]],

        ];

        foreach ($dataHomeagencies as $homeagency) {
            $newHomeagency = new Homeagency();
            $newHomeagency->setName($homeagency["name"]);
            $newHomeagency->setDirectoryname($homeagency["directoryname"]);
            $this->filesystem->mkdir($this->targetDirectory.$homeagency["directoryname"]);
            foreach ($homeagency["systems"] as $dataSystem){
                $system=$manager->getRepository(System::class)->find($dataSystem);
                $newHomeagency->addSystem($system);
                $this->filesystem->mkdir($this->targetDirectory.$homeagency["directoryname"].$system->getDirectoryName());
            }
            $manager->persist($newHomeagency);
        }
        $manager->flush();
        var_dump("hello");
    }

    public function loadSystem(ObjectManager $manager)
    {
        $datasystems=[
            ["name"=>"ids", "directoryname"=>"IDS/", "dieselFileLabel"=>"Diesel", "adblueFileLabel"=>"UREA (Ad Blue)"],
            ["name"=>"as24", "directoryname"=>"AS24/", "dieselFileLabel"=>"à changer", "adblueFileLabel"=>"à changer"],
            ["name"=>"dkv", "directoryname"=>"DKV/", "dieselFileLabel"=>"Diesel", "adblueFileLabel"=>"UREA (Ad Blue)"],
            ["name"=>"uta", "directoryname"=>"UTA/", "dieselFileLabel"=>"Gasoil", "adblueFileLabel"=>"AdBlue"],
            ["name"=>"laffon", "directoryname"=>"LAFFON/", "dieselFileLabel"=>"GASOIL", "adblueFileLabel"=>"à changer"],
            ["name"=>"tokheim", "directoryname"=>"TOKHEIM/", "dieselFileLabel"=>"Gasoil_Transics", "adblueFileLabel"=>"ADBLUE_Transics"],
            ["name"=>"manualentry", "directoryname"=>"MANUALENTRY/", "dieselFileLabel"=>"diesel", "adblueFileLabel"=>"adblue"]
        ];

        foreach ($datasystems as $system){
            $newsystem= new System();
            $newsystem->setName($system["name"]);
            $newsystem->setDirectoryName($system["directoryname"]);
            $newsystem->setDieselFileLabel($system["dieselFileLabel"]);
            $newsystem->setAdblueFielLabel($system["adblueFileLabel"]);
            $manager->persist($newsystem);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function loadUser(ObjectManager $manager)
    {
        $users=[
            ["login"=>"cholcvart", "email"=>"corentin.holcvart@hotmail.fr", "password"=>"dinosaure0", "roles"=>["ROLE_USER", "ROLE_ADMIN"], "homeagency"=>1],
            ["login"=>"dholcvart", "email"=>"d.holcvart@hotmail.fr", "password"=>"dinosaure1", "roles"=>["ROLE_USER"], "homeagency"=>1],
            ["login"=>"aholcvart", "email"=>"a.holcvart@hotmail.fr", "password"=>"dinosaure2", "roles"=>["ROLE_USER"], "homeagency"=>2],
            ["login"=>"bholcvart", "email"=>"b.holcvart@hotmail.fr", "password"=>"dinosaure3", "roles"=>["ROLE_USER"], "homeagency"=>9]
        ];

        foreach ($users as $user){
            $newuser=new User();
            $homeagency=$manager->getRepository(Homeagency::class)->find($user["homeagency"]);
            $newuser
                ->setLogin($user["login"])
                ->setEmail($user["email"])
                ->setPassword($this->passwordEncoder->encodePassword($newuser, $user["password"]))
                ->setRoles($user["roles"])
                ->setHomeagency($homeagency);
            $manager->persist($newuser);
        }
        $manager->flush();
        var_dump("hello");
    }

    public function loadProduct(ObjectManager $manager){
        $products=[
            ["name"=>"DIESEL"],
            ["name"=>"ADBLUE"]
        ];

        foreach ($products as $product){
            $newproduct=new Product();
            $newproduct->setName($product["name"]);
            $manager->persist($newproduct);
        }
        $manager->flush();
    }
}
