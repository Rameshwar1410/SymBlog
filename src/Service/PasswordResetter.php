<?php
declare (strict_types = 1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *  Used to manage user password
 */
class PasswordResetter
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var NotificationGenerator $notification An instance of notification generator  */
    private $notification;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of notification UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /**
     * Constructor to initialize variable
     * 
     * @param EntityManagerInterface $entityManager An instance of EntityManagerInterface 
     * @param UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface 
     * @param NotificationGenerator $notification An instance of NotificationGenerator 
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        NotificationGenerator $notification
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->notification = $notification;
    }

    /**
     * Password Reset
     *
     * @param array $formData Contain email id and password
     * @return bool If success then true otherwise false
     */
    public function reset(array $formData): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $userData = $userRepository->findOneBy(['email' => $formData['email']]);
        if (!$userData) {
            $this->notification->setNotification(
                'warning',
                'Entered ' . $formData['email'] . ' does not exist.
                 Please check and enter currect Email Id.');

            return false;
        }
        $encodedPassword = $this->passwordEncoder->encodePassword($userData, $formData['password']);
        $userData->setPassword($encodedPassword);
        $this->entityManager->persist($userData);
        $this->entityManager->flush();
        $this->notification->setNotification('info', 'Your password has been reseted successfully.');

        return true;
    }
}
