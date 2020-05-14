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
    private $id=null;
    private $login=null;
    public function __construct(){
        var_dump("hellohellohello2");
    }
    public function supports(Request $request)
    {
        var_dump("helloworld");
        return $request->cookies->get("jwtAuthentication") ? true : false;
    }

    public function getCredentials(Request $request)
    {
        $cookie = $request->cookies->get("jwtAuthentication");
        // Default error message
        $error = "Unable to validate session.";
        try
        {
            $decodedJwt = JWT::decode($cookie, "string", ['HS256']);
            var_dump($decodedJwt);
            $this->id=$decodedJwt->user_id;
            $this->login=$decodedJwt->login;
            return [
                'user_id' => $decodedJwt->user_id,
                'login' => $decodedJwt->login
            ];
        }
        catch(ExpiredException $e)
        {
            $error = "Session has expired.";
            var_dump($error);
        }
        catch(SignatureInvalidException $e)
        {
            // In this case, you may also want to send an email to yourself with the JWT
            // If someone uses a JWT with an invalid signature, it could
            // be a hacking attempt.
            $error = "Attempting access invalid session.";
        }
        catch(Exception $e)
        {
            // Use the default error message
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
            'id'=>$this->id,
            'login'=>$this->login
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
