<?php

namespace App\Security;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTAuthenticator extends AbstractGuardAuthenticator
{
    private $jwt_secret;
    private $jwt_path;
    private $jwt_domain;
    private $jwt_access_name;
    private $jwt_refresh_name;

    public function __construct($jwt_secret, $jwt_path, $jwt_domain, $jwt_access_name, $jwt_refresh_name){
        $this->jwt_secret=$jwt_secret;
        $this->jwt_path=$jwt_path;
        $this->jwt_domain=$jwt_domain;
        $this->jwt_access_name=$jwt_access_name;
        $this->jwt_refresh_name=$jwt_refresh_name;
    }
    public function supports(Request $request)
    {
        return $request->cookies->get($this->jwt_access_name) ? true : false;
    }

    public function getCredentials(Request $request)
    {
        $cookie = $request->cookies->get($this->jwt_access_name);
        // Default error message
        $error = "Unable to validate session.";
        try
        {
            $decodedJwt = JWT::decode($cookie, $this->jwt_secret, ['HS256']);
            return [
                'user_id' => $decodedJwt->user_id,
                'login' => $decodedJwt->login
            ];
        }
        catch(ExpiredException $e)
        {
            $error = "Session has expired.";
        }
        catch(SignatureInvalidException $e)
        {
            $error = "Attempting access invalid session.";
        }
        catch(Exception $e)
        {
        }
        throw new CustomUserMessageAuthenticationException($error);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['login']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $user->getId() === $credentials['user_id'];
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessageKey(),
        ], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    public function supportsRememberMe()
    {
    }
}
