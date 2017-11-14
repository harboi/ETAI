<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 08/11/2017
 * Time: 21:50
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/encrypt", name="encrypt")
     */
    public function encrypt(UserPasswordEncoderInterface $encoder)
    {
        $user = new \App\Entity\User();
        $plainPassword = 'admin';
        $encoded = $encoder->encodePassword($user, $plainPassword);

        return new Response(
            '<html><body>password: '.$encoded.'</body></html>'
        );
    }
}