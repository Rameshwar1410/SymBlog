<?php
declare (strict_types = 1);

namespace App\tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\PostCreator;
use PHPUnit\Framework\TestCase;
use App\Entity\{Post, User};

/**
 * Used to test PostCreator service
 * 
 * @coversDefaultClass App\Service\PostCreator
 */
class PostCreatorTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var PostCreator $postCreator An instance of PostCreator  */
    private $postCreator;
    
    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->postCreator = new PostCreator($this->entityManager);
    }

    /**
     * Used to test add method
     * 
     * @covers::add
     */
    public function testAdd()
    {
        $post = new Post();
        $user = new User();
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with(\Mockery::on(function ($post) {
                $this->assertInstanceOf(Post::class, $post);
                
                return true;
            }));
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->postCreator->add($post, $user);
    }
}
