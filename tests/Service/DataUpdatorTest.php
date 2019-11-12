<?php
declare (strict_types = 1);

namespace App\tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\DataUpdator;
use PHPUnit\Framework\TestCase;

/**
 * Used to test DataUpdator service
 * 
 * @coversDefaultClass App\Service\DataUpdator
 */
class DataUpdatorTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var DataUpdator $dataUpdator An instance of DataUpdator  */
    private $dataUpdator;
    
    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->dataUpdator = new DataUpdator($this->entityManager);
    }

    /**
     * Used to test update method
     * 
     * @covers::update
     */
    public function testUpdate()
    {
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->dataUpdator->update();
    }
}
