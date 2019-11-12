<?php
declare (strict_types = 1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Used to manage forgoted password
 */
class ForgotPasswordHelper
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var NotificationGenerator $notification An instance of NotificationGenerator  */
    private $notification;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var MailGenerator $mailGenerator An instance of MailGenerator  */
    private $mailGenerator;

    /**
     * Constructor to initialize variable
     *
     * @param EntityManagerInterface $entityManager An instance of EntityManagerInterface 
     * @param UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface 
     * @param NotificationGenerator $notification An instance of NotificationGenerator 
     * @param MailGenerator $mailGenerator An instance of MailGenerator 
     */
    public function __construct(
        EntityManagerInterface $entityManager, 
        UserPasswordEncoderInterface $passwordEncoder, 
        NotificationGenerator $notification,
        MailGenerator $mailGenerator
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->notification = $notification;
        $this->mailGenerator = $mailGenerator;
    }

    /**
     * Temprory password generator and send an email to user
     *
     * @param string $emailId User email id
     * @return bool If success then true otherwise false
     */
    public function forgotPassword(string $emailId): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $userData = $userRepository->findOneBy(['email' => $emailId]);
        if (!$userData) {
            $this->notification->setNotification(
                'warning',
                'Entered ' . $emailId . ' does not exist.
                 Please check and enter currect Email Id.'
            );

            return false;
        }
        $tempPassword = substr(md5(uniqid()), 0, 10);
        $encodedPassword = $this->passwordEncoder->encodePassword($userData, $tempPassword);
        $userData->setPassword($encodedPassword);
        $this->entityManager->flush();
        $this->mailGenerator->sendMail([
            'name' => $userData->getUserName(),
            'toEmail' => $emailId,
            'subject' => 'Temporary Password',
            'templateName' => 'emailTemplates/tempPasswordEmail.html.twig',
            'tempPassword' => $tempPassword,
        ]);
        $this->notification->setNotification(
            'info',
            'Your temporary password has been sent ot your email. Please check your email
             for temporary password.'
        );

        return true;
    }
}
