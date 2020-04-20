<?php

namespace App\DataFixtures;

use App\Entity\Homeagency;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
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
    }
}
