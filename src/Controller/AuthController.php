<?php
declare (strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Auth controller use to manage login and logout
 */
class AuthController extends AbstractController
{
    /**
     * Login 
     * 
     * @param AuthenticationUtils $authenticationUtils An instance of AuthenticationUtils
     * @return Response An instance of Response
     * @Route("/login", name="app.login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {           
            return $this->redirectToRoute('all.users.post');
        }

        return $this->render('security/login.html.twig', [
            'lastUsername' => $authenticationUtils->getLastUsername(), 
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * Logout 
     * 
     * @Route("/logout", name="app.logout")
     */
    public function logout(): void
    {
        
    }
}
