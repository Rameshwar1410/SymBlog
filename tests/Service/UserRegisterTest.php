<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use App\Service\{UserRegister, MailGenerator, NotificationGenerator, UserCreator};
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;;

/**
 * Used to test UserRegister service
 * 
 * @coversDefaultClass App\Service\UserRegister
 */
class UserRegisterTest extends TestCase
{
    /** @var UserRegister $userRegister An instance of UserRegister  */
    private $userRegister;

    /** @var MailGenerator $mailGenerator An instance of MailGenerator  */
    private $mailGenerator;

    /** @var NotificationGenerator $notification An instance of NotificationGenerator  */
    private $notification;

    /** @var UserCreator $userCreator An instance of UserCreator  */
    private $userCreator;

    public function setUp(): void
    {
        $this->mailGenerator = \Mockery::mock(MailGenerator::class);
        $this->notification = \Mockery::mock(NotificationGenerator::class);
        $this->userCreator = \Mockery::mock(UserCreator::class);
        $this->userRegister = new UserRegister(
            $this->notification,
            $this->mailGenerator,
            $this->userCreator
        );
    }

    /**
     * Used to test add method
     * 
     * @covers::add
     */
    public function testAdd(): void
    {
        $user = new User();
        $user->setPassword('111212221');
        $user->setEmail('demotest@gmail.com');
        $user->setFirstName('test');
        $user->setImage('12345');
        $user->setLastName('142141');
        $user->setUserName('test');
        $uploadedFile = \Mockery::mock(UploadedFile::class);
        $this->userCreator->shouldReceive('add')
            ->once()
            ->with($user, '/images', $uploadedFile);
        $this->mailGenerator->shouldReceive('sendMail')
            ->once()
            ->with([
                'name' => 'test',
                'toEmail' => 'demotest@gmail.com',
                'subject' => 'Thanks for Registering at Symfony Blog',
                'templateName' => 'emailTemplates/registerEmail.html.twig',
            ])
            ->andReturn(true);
        $this->notification->shouldReceive('setNotification')
            ->once()
            ->with(
                'info',
                'Your account has been created successfully and is ready to use'
            );
        $this->userRegister->add(
            $user,
            '/images',
            $uploadedFile
        );
        $this->assertEquals('test', $user->getFirstName());
    }
}
