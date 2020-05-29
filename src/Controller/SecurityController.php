<?php

namespace App\Controller;

use App\Entity\User;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{
    private $jwt_secret;
    private $jwt_path;
    private $jwt_domain;
    private $jwt_access_name;
    private $jwt_refresh_name;
    public function __construct($jwt_secret, $jwt_path, $jwt_domain, $jwt_access_name, $jwt_refresh_name)
    {
        $this->jwt_secret=$jwt_secret;
        $this->jwt_path=$jwt_path;
        $this->jwt_domain=$jwt_domain;
        $this->jwt_access_name=$jwt_access_name;
        $this->jwt_refresh_name=$jwt_refresh_name;
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        return $this->json(['result' => true]);
    }

    /**
     * @Route("/logout", name="api_logout", methods={"GET"})
     * @param Request $resquest
     * @return JsonResponse
     */
    public function logout(Request $resquest)
    {
        $refreshTokenString=JWT::decode($resquest->cookies->get($this->jwt_refresh_name), $this->jwt_secret, ["HS256"])->refresh_token;
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('apiToken' => $refreshTokenString));
        $user->setApiToken(null);
        $this->getDoctrine()->getManager()->flush();
        setcookie($this->jwt_refresh_name, null, time(), $this->jwt_path, $this->jwt_domain, false, true);
        setcookie($this->jwt_access_name, null, time(), $this->jwt_path, $this->jwt_domain, false, true);
        return new JsonResponse([]);
    }
}
