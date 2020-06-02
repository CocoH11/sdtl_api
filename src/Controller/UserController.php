<?php

namespace App\Controller;

use App\Entity\Homeagency;
use App\Entity\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;
    private $user_user_name;
    private $user_login_name;
    private $user_email_name;
    private $user_password_name;
    private $user_isadmin_name;
    private $user_homeagency_name;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, $user_user_name, $user_login_name, $user_email_name, $user_password_name, $user_isadmin_name, $user_homeagency_name){
        $this->passwordEncoder = $passwordEncoder;
        $this->user_user_name=$user_user_name;
        $this->user_login_name=$user_login_name;
        $this->user_email_name=$user_email_name;
        $this->user_password_name=$user_password_name;
        $this->user_isadmin_name=$user_isadmin_name;
        $this->user_homeagency_name=$user_homeagency_name;
    }

    /**
     * @Route("/user", name="changePassword", methods={"PATCH"})
     * @param Request $request
     * @return JsonResponse
     */
    public function changePasssord(Request $request){
        $password=json_decode($request->getContent(), true)[$this->user_password_name];
        $user=$this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($password, 200);
    }

    /**
     * @Route("/admin/user", name="addUser", methods={"PUT"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function addUser(Request $request, ValidatorInterface $validator){
        $user=json_decode($request->getContent(), true)[$this->user_user_name];
        $homeagency=$this->getDoctrine()->getRepository(Homeagency::class)->find($user[$this->user_homeagency_name]);


        $newuser= new User();
        $newuser
            ->setLogin($user[$this->user_login_name])
            ->setEmail($user[$this->user_email_name])
            ->setHomeagency($homeagency)
            ->setPassword($this->passwordEncoder->encodePassword($newuser, $user[$this->user_password_name]))
        ;
        if ($user[$this->user_isadmin_name])$newuser->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        else $newuser->setRoles(["ROLE_USER"]);
        $errors=$validator->validate($newuser);
        $errorsmessages=[];
        if (count($errors)>0){
            foreach ($errors as $error){
                array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);
            }
        }else{
            $this->getDoctrine()->getManager()->persist($newuser);
            $this->getDoctrine()->getManager()->flush();
        }

        return new JsonResponse($errorsmessages, 200);
    }

    /**
     * @Route("/admin/user/{id}", name="deleteUser", methods={"DELETE"})
     * @ParamConverter(name="user", class="App:User")
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function deleteUser(Request $request, User $user){
        $error=null;
        if ($user) {
            $this->getDoctrine()->getManager()->remove($user);
            $this->getDoctrine()->getManager()->flush();
        }else $error="l'utilisateur à supprimer n'existe pas";

        return new JsonResponse($error, 200);
    }
    /*
    public function addUsers(Request $request, ValidatorInterface $validator){
        $users=json_decode($request->getContent(), true)["users"];
        $userserrorsmessages=[];
        foreach ($users as $user){
            $homeagency=$this->getDoctrine()->getRepository(Homeagency::class)->find($user["homeagency"]);
            $newuser= new User();

            $newuser
                ->setLogin($user["login"])
                ->setHomeagency($homeagency)
                ->setPassword($this->passwordEncoder->encodePassword($newuser, $user["password"]))
                ->setRoles($user["roles"])
            ;
            $errors=$validator->validate($newuser);
            $errorsmessages=[];
            if (count($errors)>0){
                foreach ($errors as $error){
                    array_push($errorsmessages, [$error->getPropertyPath()=>$error->getMessage()]);
                }
            }else $this->getDoctrine()->getManager()->persist($newuser);
            array_push($userserrorsmessages, $errorsmessages);
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($userserrorsmessages, 200);
    }*/
}
