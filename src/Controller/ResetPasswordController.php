<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ResetPasswordFormType;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PasswordResetter;

/**
 * Controller use to manage password reset
 */
class ResetPasswordController extends AbstractController
{
    /** @var PasswordResetter $passwordResetter An instance of PasswordResetter */
    private $passwordResetter;

    /**
     * Constructor to initialize variable
     * 
     * @param PasswordResetter $passwordResetter An instance of PasswordResetter
     */
    public function __construct(PasswordResetter $passwordResetter)
    {
        $this->passwordResetter = $passwordResetter;
    }
    
    /**
     * Reset password
     * 
     * @Route("user/reset/password", name="reset.password")
     * @param Request $request An instance of Request
     * @return Response An instance of Response
     */
    public function resetPassword(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->passwordResetter->reset($form->getdata())) {

            return $this->redirectToRoute('reset.password');
        }

        return $this->render('reset_password/resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
