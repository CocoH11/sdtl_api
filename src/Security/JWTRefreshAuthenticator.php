<?php


namespace App\Security;


use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTRefreshAuthenticator extends AbstractGuardAuthenticator
{
    private $manager;
    private $secret;
    public function __construct(EntityManagerInterface $manager, $secret)
    {
        $this->manager=$manager;
        $this->secret=$secret;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => 'Access Denied'.'hello'
        ]);
        // TODO: Implement start() method.
    }

    public function supports(Request $request)
    {
        var_dump("refreshauthenticator");
        return true;
    }

    public function getCredentials(Request $request)
    {
        $decodedJwt=JWT::decode($request->cookies->get("jwtRefresh"), $this->secret, ["HS256"]);
        return ['refreshToken' => $decodedJwt->refresh_token];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user=$this->manager->getRepository(User::class)->findOneBy(["apiToken"=>$credentials["refreshToken"]]);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessageKey()
        ], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        //$oldJwtAuthentication=JWT::decode($request->cookies->get("jwtAuthentication"),$secret, ['HS256']);
        if (!$request->cookies->get("jwtAuthentication")){
            var_dump("refreshauthenticator accesstoken invalide");
            $request->cookies->remove("jwtRefresh");
            $expireTimeAuthentication = time() +10;
            $tokenPayloadAuthentication = [
                'user_id' => $token->getUser()->getId(),
                'login'   => $token->getUser()->getUsername(),
                'exp'     => $expireTimeAuthentication
            ];
            $jwtAuthentication = JWT::encode($tokenPayloadAuthentication, $this->secret);
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
            $jwtRefresh=JWT::encode($tokenPayLoadRefresh, $this->secret);
            setcookie("jwtAuthentication", $jwtAuthentication,$expireTimeAuthentication, "/", null, false, true);
            setcookie("jwtRefresh", $jwtRefresh, $expireTimeRefresh, "/", null, false, true);
        }
        return null;
    }

    public function supportsRememberMe()
    {
    }

    private function RandomToken(int $length)
    {
        return bin2hex(random_bytes($length));
    }
}