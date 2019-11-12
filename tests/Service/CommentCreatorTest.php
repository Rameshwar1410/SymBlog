<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\CommentCreator;
use App\Entity\{Comment, Post, User};
use PHPUnit\Framework\TestCase;

/**
 * Used to test CommentCreator service
 * 
 * @coversDefaultClass App\Service\CommentCreator
 */
class CommentCreatorTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var CommentCreator $commentCreator An instance of CommentCreator  */
    private $commentCreator;

    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->commentService = new CommentCreator($this->entityManager);   
    }
    
    /**
     * Used to test add method
     * 
     * @covers::add
     */
    public function testAdd()
    {
        $comment = new Comment();
        $user = \Mockery::mock(User::class);
        $postRepository = \Mockery::mock(PostRepository::class);
        $this->entityManager->shouldReceive('getRepository')
            ->once()
            ->with(Post::class)
            ->andReturn($postRepository);
        $postRepository->shouldReceive('find')
            ->once()
            ->with(4)
            ->andReturn(\Mockery::mock(Post::class));
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with(\Mockery::on(function ($comment) {
                $this->assertInstanceOf(Comment::class, $comment);
                
                return true;
            }))
            ->andReturn(true);
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->commentService->add(
            [
                "comment" => "aaaa",
                "post_id" => "4"
            ],
            $user
        );
    }
}
