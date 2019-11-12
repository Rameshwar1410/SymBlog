<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;

/**
 * UserCreator for add a new user
 */
class UserCreator
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var FileUploader $fileUploader An instance of FileUploader  */
    private $fileUploader;

    /**
     * Constructor to initialize variable
     * 
     * @param EntityManagerInterface $entityManager An instance of EntityManagerInterface 
     * @param UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface 
     * @param FileUploader $fileUploader An instance of FileUploader 
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder, 
        FileUploader $fileUploader
    ) 
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->fileUploader = $fileUploader;
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
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $fileName = $this->fileUploader->profileImage($uploadedFile, $imagePath);
        $user->setPassword($encodedPassword);
        $user->setRoles(['ROLE_USER']);
        $user->setImage($fileName);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
