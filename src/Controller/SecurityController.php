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
    private string $jwt_secret;
    private string $jwt_path;
    private string $jwt_domain;
    private string $jwt_access_name;
    private string $jwt_refresh_name;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $refreshTokenString=JWT::decode($request->cookies->get($this->jwt_refresh_name), $this->jwt_secret, ["HS256"])->jwt_refresh;
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('apiToken' => $refreshTokenString));
        $user->setApiToken(null);
        $this->getDoctrine()->getManager()->flush();
        setcookie($this->jwt_refresh_name, null, time(), $this->jwt_path, $this->jwt_domain, false, true);
        setcookie($this->jwt_access_name, null, time(), $this->jwt_path, $this->jwt_domain, false, true);
        return new JsonResponse(["authentication"=>false]);
    }

    /**
     * @Route("/isAuthenticated", name="isAuthenticated", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function isAuthenticated(Request $request){
        return new JsonResponse(["authentication"=>$request->cookies->get($this->jwt_refresh_name)? true : false]);
    }
}
