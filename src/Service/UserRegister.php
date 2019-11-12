<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;

/**
 * Used to new user registration
 */
class UserRegister
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var MailGenerator $mailGenerator An instance of MailGenerator  */
    private $mailGenerator;

    /** @var NotificationGenerator $notification An instance of NotificationGenerator  */
    private $notification;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var FileUploader $fileUploader An instance of FileUploader  */
    private $fileUploader;

    /** @var UserCreator $userCreator An instance of UserCreator  */
    private $userCreator;

    /**
     * Constructor to initialize variable
     * 
     * @param EntityManagerInterface $entityManager An instance of EntityManagerInterface 
     * @param NotificationGenerator $notificationGenerator An instance of NotificationGenerator
     * @param MailGenerator $mailGenerator An instance of MailGenerator 
     * @param UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface 
     * @param FileUploader $fileUploader An instance of FileUploader 
     * @param UserCreator $userCreator An instance of UserCreator
     */
    public function __construct(
        NotificationGenerator $notificationGenerator,
        MailGenerator $mailGenerator,
        UserCreator $userCreator
    ) {
        $this->notification = $notificationGenerator;
        $this->mailGenerator = $mailGenerator;
        $this->userCreator = $userCreator;
    }

    /**
     * Used to add new user
     *
     * @param User $user An instance of User
     * @param string $imagePath An profile image path
     * @param UploadedFile $uploadedFile An instance of UploadedFile
     */
    public function add(User $user, string $imagePath, UploadedFile $uploadedFile): void
    {
        $this->userCreator->add($user, $imagePath, $uploadedFile);
        $this->mailGenerator->sendMail([
            'name' => $user->getFirstName(),
            'toEmail' => $user->getEmail(),
            'subject' => 'Thanks for Registering at Symfony Blog',
            'templateName' => 'emailTemplates/registerEmail.html.twig',
        ]);
        $this->notification->setNotification(
            'info',
            'Your account has been created successfully and is ready to use'
        );
    }
}
