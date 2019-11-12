<?php
declare (strict_types = 1);

namespace App\Test\Serivce;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\UserDeletor;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

/**
 * Used to test UserDeletor service
 * 
 * @coversDefaultClass App\Service\UserDeletor
 */
class UserDeletorTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;
    
    /** @var UserDeletor $userDeletor An instance of UserDeletor  */
    private $userDeletor;

    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->userDeletor = new UserDeletor($this->entityManager);
    }

    /**
     * Used to test remove method
     * 
     * @covers::remove
     */
    public function testRemove()
    {
        $user = new User();
        $this->entityManager->shouldReceive('remove')
            ->once()
            ->with(\Mockery::on(function ($user) {
                $this->assertInstanceOf(User::class, $user);
                
                return true;
            }));
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->userDeletor->remove(
            $user,
            ''
        );
    }
}
