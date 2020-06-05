<?php


namespace App\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTRefreshAuthenticator extends AbstractGuardAuthenticator
{
    private $manager;
    private $jwt_secret;
    private $jwt_path;
    private $jwt_domain;
    private $jwt_access_name;
    private $jwt_refresh_name;

    public function __construct(EntityManagerInterface $manager, $jwt_secret, $jwt_path, $jwt_domain, $jwt_access_name, $jwt_refresh_name)
    {
        $this->manager=$manager;
        $this->jwt_secret=$jwt_secret;
        $this->jwt_path=$jwt_path;
        $this->jwt_domain=$jwt_domain;
        $this->jwt_access_name=$jwt_access_name;
        $this->jwt_refresh_name=$jwt_refresh_name;
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
        return $request->cookies->get($this->jwt_refresh_name)? true : false;
    }

    public function getCredentials(Request $request)
    {
        $decodedJwt=JWT::decode($request->cookies->get($this->jwt_refresh_name), $this->jwt_secret, ["HS256"]);
        return ['refreshToken' => $decodedJwt->jwt_refresh];
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
        $cookie=$request->cookies->get($this->jwt_access_name);
        if ($cookie){
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
                $this->jwt_refresh_name=>$refreshTokenString,
                'exp'=>$expireTimeRefresh
            ];
            $jwtRefresh=JWT::encode($tokenPayLoadRefresh, $this->jwt_secret);
            setcookie($this->jwt_access_name, $jwtAuthentication,$expireTimeAuthentication, $this->jwt_path, $this->jwt_domain, false, true);
            setcookie($this->jwt_refresh_name, $jwtRefresh, $expireTimeRefresh, $this->jwt_path, $this->jwt_domain, false, true);
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