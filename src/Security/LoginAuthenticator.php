<?php

namespace App\Security;


use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LoginAuthenticator extends AbstractGuardAuthenticator
{
    private $passwordEncoder;
    private $refreshStatus=false;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        var_dump("hellohellohello1");
    }

    public function supports(Request $request)
    {
        return $request->get("_route") === "api_login" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        $data=json_decode($request->getContent(), true);
        $login=$data["login"];
        $password=$data["password"];
        if (!$login && !$password){
            $cookie=$request->cookies->get("jwtRefresh");
            $data=JWT::decode($cookie, "string");
            $login=$data["login"];
            $password=$data["password"];
        }
        return [
            'login' => $login,
            'password' => $password,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['login']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessageKey()
        ], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $expireTimeAuthentication = time() +3600;
        $tokenPayloadAuthentication = [
            'user_id' => $token->getUser()->getId(),
            'login'   => $token->getUser()->getUsername(),
            'exp'     => $expireTimeAuthentication
        ];
        $jwtAuthentication = JWT::encode($tokenPayloadAuthentication, "string");

        /*$expireTimeRefresh = time() + 3600;
        $tokenPayLoadRefresh=[
            'user_id'=>$token->getUser()->getId(),
            'password'=>$token->getUser()->getUsername(),
            'exp'=>$expireTimeRefresh
        ];
        $jwtRefresh=JWT::encode($tokenPayLoadRefresh, "string");*/

        setcookie("jwtAuthentication", $jwtAuthentication,null);
        //setcookie("jwtRefresh", $jwtRefresh);
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => 'Access Denied'.'hello'
        ]);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
