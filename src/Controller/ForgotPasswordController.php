<?php
declare (strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ForgotPasswordFormType;
use App\Service\ForgotPasswordHelper;

/**
 * Used to manage forgot password
 */
class ForgotPasswordController extends AbstractController
{
    /**
     * Forgot password
     * 
     * @Route("/forgot/password", name="forgot.password")
     * @param Request $request An instance of Request
     * @param ForgotPasswordHelper $forgotPasswordHelper An instance of ForgotPasswordHelper
     * @return Response An instance of Response
     */
    public function forgotPassword(Request $request, ForgotPasswordHelper $forgotPasswordHelper): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);
        if (
            $form->isSubmitted() && 
            $form->isValid() && 
            $forgotPasswordHelper->forgotPassword($form->getdata()['email'])
        ) {
            
            return $this->redirectToRoute('app.login');
        }

        return $this->render('forgot_password/forgotPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
