<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

/**
 * Used to remove user
 */
class UserDeletor
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface */
    private $entityManager;

    /**
     * Constructor to initialize variable
     * 
     * @param EntityManagerInterface $entityManager An instance of EntityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

     /**
     * Delete blog Data
     * 
     * @param User $user An instance of User
     * @param string $imagePath Image uploaded directory path
     */
    public function remove(User $user, string $imagePath): void
    {
        if ($imagePath) {
            unlink($imagePath.'/'.$user->getimage());
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
