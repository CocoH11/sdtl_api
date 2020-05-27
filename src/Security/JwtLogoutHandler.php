<?php
namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class JwtLogoutHandler implements LogoutSuccessHandlerInterface
{
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager=$manager;
    }

    public function onLogoutSuccess(Request $request)
    {
        $response = new JsonResponse(['result' => true]);
        $response->headers->clearCookie("jwtAuthentication", "/");
        $response->headers->clearCookie("jwtRefresh", "/");

        return $response;
    }
}