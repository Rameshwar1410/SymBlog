<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Entity\User;

/**
 * PostCreator for add a new Post
 */
class PostCreator
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
     * Add data for post
     * 
     * @param Post $post An instance of Post entity
     * @param User $user An instance of User entity
     */
    public function add(Post $postData, User $user): void
    {
        $postData->setPostedBy($user);
        $this->entityManager->persist($postData);
        $this->entityManager->flush();
    }
}
