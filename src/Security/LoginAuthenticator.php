<?php

namespace App\Security;


use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
    private $manager;
    private $jwt_secret;
    private $jwt_domain;
    private $jwt_path;
    private $jwt_access_name;
    private $jwt_refresh_name;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager, $jwt_secret, $jwt_domain, $jwt_path, $jwt_access_name, $jwt_refresh_name)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager=$manager;
        $this->jwt_secret=$jwt_secret;
        $this->jwt_domain=$jwt_domain;
        $this->jwt_path=$jwt_path;
        $this->jwt_access_name=$jwt_access_name;
        $this->jwt_refresh_name=$jwt_refresh_name;
    }

    public function supports(Request $request)
    {
        var_dump("loginauthenticator");
        return $request->get("_route") === "api_login" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        $data=json_decode($request->getContent(), true);
        $login=$data["login"];
        $password=$data["password"];
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
        var_dump("loginauthenticationsuccess");
        //Access token
        $expireTimeAuthentication = time() +10;
        $tokenPayloadAuthentication = [
            'user_id' => $token->getUser()->getId(),
            'login'   => $token->getUser()->getUsername(),
            'exp'     => $expireTimeAuthentication
        ];
        $jwtAuthentication = JWT::encode($tokenPayloadAuthentication, $this->jwt_secret);
        //Create refresh token
        $refreshTokenString=$this->RandomToken(32);
        //Save the refresh token in the database
        $user=$this->manager->getRepository(User::class)->find($token->getUser()->getId());
        $user->setApiToken($refreshTokenString);
        $this->manager->flush();
        //Refresh token
        $expireTimeRefresh = time() + 3600;
        $tokenPayLoadRefresh=[
            'refresh_token'=>$refreshTokenString,
            'exp'=>$expireTimeRefresh
        ];
        $jwtRefresh=JWT::encode($tokenPayLoadRefresh, $this->jwt_secret);
        setcookie($this->jwt_access_name, $jwtAuthentication,$expireTimeAuthentication, $this->jwt_path, $this->jwt_domain, false, true);
        setcookie($this->jwt_refresh_name, $jwtRefresh, $expireTimeRefresh, $this->jwt_path, $this->jwt_domain, false, true);
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

    private function RandomToken(int $length)
    {
        return bin2hex(random_bytes($length));
    }
}
