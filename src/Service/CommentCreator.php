<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Comment, Post, User};

/**
 * CommentCreator for add a new comment
 */
class CommentCreator
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
     * Add comment of blog post
     * 
     * @param array $commentData Contain post id and comment
     * @param User $user An instance of User
     */
    public function add(array $commentData, User $user): void
    {
        $comment = new Comment();
        $post = $this->entityManager->getRepository(Post::class)->find($commentData['post_id']);
        $comment->setComment($commentData['comment']);
        $comment->setPost($post);
        $comment->setUser($user);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
