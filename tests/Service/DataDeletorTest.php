<?php
declare (strict_types = 1);

namespace App\Test\Serivce;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\DataDeletor;
use PHPUnit\Framework\TestCase;
use App\Entity\Comment;

/**
 * Used to test DataDeletor service
 * 
 * @coversDefaultClass App\Service\DataDeletor
 */
class DataDeletorTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var DataDeletor $dataDeletor An instance of DataDeletor  */
    private $dataDeletor;

    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->dataDeletor = new DataDeletor($this->entityManager);
    }

    /**
     * Used to test remove method
     * 
     * @covers::remove
     */
    public function testRemove()
    {
        $comment = new Comment();
        $this->entityManager->shouldReceive('remove')
            ->once()
            ->with(\Mockery::on(function ($comment) {
                $this->assertInstanceOf(Comment::class, $comment);
                
                return true;
            }));
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->dataDeletor->remove($comment);

    }
}
