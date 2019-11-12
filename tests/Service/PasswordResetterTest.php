<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\{PasswordResetter, NotificationGenerator};
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Used to test PasswordResetter service
 *  
 * @coversDefaultClass App\Service\PasswordResetter
 */
class PasswordResetterTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var PasswordResetter $passwordResetter An instance of PasswordResetter  */
    private $passwordResetter;

    /** @var NotificationGenerator $notification An instance of NotificationGenerator  */
    private $notification;

    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->passwordEncoder = \Mockery::mock(UserPasswordEncoderInterface::class);
        $this->notification = \Mockery::mock(NotificationGenerator::class);
        $this->passwordResetter = new PasswordResetter(
            $this->entityManager,
            $this->passwordEncoder,
            $this->notification,
        );
    }

    /**
     * Used to test for Reset method
     * 
     * @covers::reset
     */
    public function testReset()
    {
        $user = new User();
        $userRepository = \Mockery::mock(UserRepository::class);
        $formData = ['email' => "ram.birajdar55@gmail.com", "password" => "124asd12"];
        $this->entityManager->shouldReceive('getRepository')
            ->once()
            ->with(User::class)
            ->andReturn($userRepository);
        $userRepository->shouldReceive('findOneBy')
            ->once()
            ->with(['email' => $formData['email']])
            ->andReturn($user);
        $this->passwordEncoder->shouldReceive('encodePassword')
            ->once()
            ->with($user, $formData['password'])
            ->andReturn('asdas5454');
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with($user)
            ->andReturn(true);
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->notification->shouldReceive('setNotification')
            ->once()
            ->with(
                'info',
                'Your password has been reseted successfully.'
            );
        $this->passwordResetter->reset($formData);
        $this->assertEquals('asdas5454', $user->getPassword());
    }
}
