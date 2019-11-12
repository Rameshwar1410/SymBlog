<?php

/**
 * This file contains the unit tests for FeedImportController
 *
 * PHP Version 7
 *
 * @author Pravin Kudale <p.kudale@easternenterprise.com>
 */

declare(strict_types=1);

namespace HijobFeedProcessor\Tests\Controller;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use HijobFeedProcessor\Controller\FeedImportController;
use HijobFeedProcessor\Services\Import\FeedImporter;
use HijobFeedProcessor\Repository\FeedRepository;
use HijobFeedProcessor\Entity\Feed;
use HijobFeedProcessor\EventListener\FeedImportListener;
use Symfony\Component\Lock\Lock;

/**
 * Unit tests for the FeedImportController
 *
 * @coversDefaultClass HijobFeedProcessor\Controller\FeedImportController;
 * @author Pravin Kudale <p.kudale@easternenterprise.com>
 */
class FeedImportControllerTest extends MockeryTestCase
{
    /* @var FeedImportController $controller An instance of FeedImportController /
    private $controller;

    /* @var Mockery\MockInterface $containerMock The container mock /
    private $containerMock;

    /* @var Mockery\MockInterface $feedImporterMock The FeedImporter service mock /
    private $feedImporterMock;
    
    /* @var Mockery\MockInterface $feedImportListenerMock The FeedImportListener mock /
    private $feedImportListenerMock;
    
    /**
     * Set up test
     *
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    public function setUp()
    {
        $this->containerMock = Mockery::mock(ContainerInterface::class);
        $this->feedImporterMock = Mockery::mock(FeedImporter::class);
        $this->feedImportListenerMock = Mockery::mock(
            FeedImportListener::class
        );
        
        $this->controller = new FeedImportController(
            $this->feedImporterMock, 
            $this->feedImportListenerMock
        );
        $this->controller->setContainer($this->containerMock);
    }
    
    /**
     * Tests importing single feed
     * 
     * @covers ::import
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    public function testImportingSingleFeed() 
    {
        $feedId = 11;
        $feed = new Feed(['id' => 11]);
        $feedRepositoryMock = Mockery::mock(FeedRepository::class)
            ->shouldReceive('find')
            ->once()
            ->with($feedId)
            ->andReturn($feed)
            ->getMock();
        $this->setCommonExpectationsForTests($feedRepositoryMock);
        $this->feedImportListenerMock->shouldReceive('setFeed')
            ->once()
            ->with($feed)
            ->andReturn($this->feedImportListenerMock);
        $this->feedImportListenerMock->shouldReceive('setLock')
            ->once()
            ->with(Mockery::on(function ($lock) {
                $this->assertInstanceOf(Lock::class, $lock);
                
                return true;
            }))
            ->andReturn($this->feedImportListenerMock);
        
        $response = $this->controller->import($feedId);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(
            Response::HTTP_NO_CONTENT, 
            $response->getStatusCode()
        );
        $this->invokeMethod($this->controller, 'release');
    }
    
    /**
     * Tests importing feed when lock is acquired
     * 
     * @covers ::import
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    public function testImportingFeedWhenLockIsAcquired()
    {
        $feedId = 11;
        $feed = new Feed(['id' => 11]);
        $feedRepositoryMock = Mockery::mock(FeedRepository::class)
            ->shouldReceive('find')
            ->once()
            ->with($feedId)
            ->andReturn($feed)
            ->getMock();
        $this->setCommonExpectationsForTests($feedRepositoryMock);
        $this->feedImportListenerMock->shouldReceive('setFeed')
            ->once()
            ->with($feed)
            ->andReturn($this->feedImportListenerMock);
        $this->feedImportListenerMock->shouldReceive('setLock')
            ->once()
            ->with(Mockery::on(function ($lock) {
                $this->assertInstanceOf(Lock::class, $lock);
                
                return true;
            }))
            ->andReturn($this->feedImportListenerMock);
        
        $firstController = new FeedImportController(
            $this->feedImporterMock, 
            $this->feedImportListenerMock
        );
        $firstController->setContainer($this->containerMock);    
        $response = $firstController->import($feedId);
        $this->assertEquals(
            Response::HTTP_NO_CONTENT, 
            $response->getStatusCode()
        );
        
        $secondFeedId = 12;
        $feedRepositoryMock = Mockery::mock(FeedRepository::class)
            ->shouldReceive('find')
            ->once()
            ->with($secondFeedId)
            ->andReturn(new Feed(['id' => 12]))
            ->getMock();
        $this->setCommonExpectationsForTests($feedRepositoryMock);
        
        $secondController = new FeedImportController(
            $this->feedImporterMock, 
            $this->feedImportListenerMock
        );
        $secondController->setContainer($this->containerMock);
        $response = $secondController->import($secondFeedId);
        $this->assertEquals(
            Response::HTTP_CONFLICT, 
            $response->getStatusCode()
        );
        
        $this->invokeMethod($this->controller, 'release');
    }

    /**
     * Tests importing single feed when feed not found
     * 
     * @covers ::import
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage No feed found
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    public function testImportingSingleFeedWhenFeedNotFound() 
    {
        $feedId = 11;
        $feedRepositoryMock = Mockery::mock(FeedRepository::class)
            ->shouldReceive('find')
            ->once()
            ->with($feedId)
            ->andReturnNull()
            ->getMock();
        $this->setCommonExpectationsForTests($feedRepositoryMock);
        
        $this->controller->import($feedId);
    }
    
    /**
     * Tests importing multiple feeds
     * 
     * @covers ::importAll
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    public function testImportingMultipleFeeds() 
    {
        $feeds = [new Feed(['id' => 11]), new Feed(['id' => 12])];
        $feedRepositoryMock = Mockery::mock(FeedRepository::class)
            ->shouldReceive('findAll')
            ->once()
            ->withNoArgs()
            ->andReturn($feeds)
            ->getMock();
        $this->setCommonExpectationsForTests($feedRepositoryMock);
        $this->feedImporterMock->shouldReceive('importAll')
            ->once()
            ->with($feeds);
        
        $response = $this->controller->importAll();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(
            Response::HTTP_NO_CONTENT, 
            $response->getStatusCode()
        );
    }
    
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     * @return mixed Method return.
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    private function invokeMethod(
        &$object, 
        string $methodName, 
        array $parameters = []
    ) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Set common expectations required for all tests
     * 
     * @param Mockery\MockInterface $feedRepositoryMock The mock of FeedRepository
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    private function setCommonExpectationsForTests(
        Mockery\MockInterface $feedRepositoryMock
    ) {
        $registryMock = Mockery::mock(Registry::class)
            ->shouldReceive('getRepository')
            ->once()
            ->with('feed:Feed')
            ->andReturn($feedRepositoryMock)
            ->getMock();
        $this->containerMock->shouldReceive('has')
            ->once()
            ->with('doctrine')
            ->andReturn(true);
        $this->containerMock->shouldReceive('get')
            ->once()
            ->with('doctrine')
            ->andReturn($registryMock);
    }
}