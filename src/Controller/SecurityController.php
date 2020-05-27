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
        $refreshTokenString=JWT::decode($resquest->cookies->get("jwtRefresh"), "string", ["HS256"])->refresh_token;
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('apiToken' => $refreshTokenString));
        $user->setApiToken(null);
        $this->getDoctrine()->getManager()->flush();
        setcookie("jwtRefresh", null, time(), "/", null, false, true);
        setcookie("jwtAuthentication", null, time(), "/", null, false, true);
        return new JsonResponse([]);
    }
}
