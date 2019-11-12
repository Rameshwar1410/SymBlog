<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use App\Service\NotificationGenerator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PHPUnit\Framework\TestCase;

/**
 * Used to test NotificationGenerator service
 * 
 * @coversDefaultClass App\Service\NotificationGenerator
 */
class NotificationGeneratorTest extends TestCase
{
    /** @var NotificationGenerator $notification An instance of NotificationGenerator  */
    private $notification;

    /** @var SessionInterface $session An instance of SessionInterface  */
    private $session;

    public function setUp()
    {
        $this->session = \Mockery::mock(SessionInterface::class);
        $this->notification = new NotificationGenerator($this->session);
    }

    /**
     * Used to test setNotification method
     * 
     * @covers ::setNotification
     */
    public function testSetNotification()
    {
        $this->session->shouldReceive('getFlashBag')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();
        $this->session->shouldReceive('add')
            ->once()
            ->with(
                'info',
                'Test notification'
            );
        $this->notification->setNotification(
            'info',
            'Test notification'
        );
    }
}
