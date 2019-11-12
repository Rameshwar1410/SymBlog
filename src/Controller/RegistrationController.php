<?php
declare (strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Entity\User;
use App\Form\RegisterFormType;
use App\Service\UserRegister;

/**
 *  Used to manage user registration
 */
class RegistrationController extends AbstractController
{
    /**
     * Used to register new user
     * 
     * @Route("/registration", name="registration")
     * @param Request $request An instance of Request
     * @param UserRegister $userRegister An instance of UserRegister
     * @return Response An instance of Response
     */
    public function register(Request $request, UserRegister $userRegister): Response
    {
        if ($this->getUser()) {
            
            return $this->redirectToRoute('all.users.post');
        }
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user, [
            'action' => $this->generateUrl('registration'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRegister->add(
                $user,
                $this->getParameter('uploades_directory'),
                $form->get('image')->getdata()
            );

            return $this->redirectToRoute('app.login');
        }

        return $this->render('registration/register.html.twig', [
            'controller_name' => 'RegistrationController',
            'register' => $form->createView()
        ]);
    }
}
