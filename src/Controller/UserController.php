<?php

namespace App\Controller;

use App\Entity\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/user", name="changePassword", methods={"PATCH"})
     */
    public function changePasssord(Request $request){
        $cookie = $request->cookies->get("jwt");
        $password=json_decode($request->getContent(), true)["password"];
        // Default error message
        $error = "Unable to validate session.";
        try
        {
            $decodedJwt = JWT::decode($cookie, "string", ['HS256']);

            $user=$this->getDoctrine()->getRepository(User::class)->find($decodedJwt->user_id);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse($password, 200);
        }
        catch(ExpiredException $e)
        {
            $error = "Session has expired.";
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
}
