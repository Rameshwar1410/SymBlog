<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\{ForgotPasswordHelper, MailGenerator, NotificationGenerator};
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Used to test ForgotPasswordHelper service
 * 
 * @coversDefaultClass App\Service\ForgotPasswordHelper
 */
class ForgotPasswordHelperTest extends TestCase
{
    /** @var ForgotPasswordHelper $forgotPasswordHelper An instance of ForgotPasswordHelper  */
    private $forgotPasswordHelper;

    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var NotificationGenerator $notification An instance of NotificationGenerator  */
    private $notification;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var MailGenerator $mailGenerator An instance of MailGenerator  */
    private $mailGenerator;

    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->passwordEncoder = \Mockery::mock(UserPasswordEncoderInterface::class);
        $this->mailGenerator = \Mockery::mock(MailGenerator::class);
        $this->notification = \Mockery::mock(NotificationGenerator::class);
        $this->forgotPasswordHelper = new ForgotPasswordHelper(
            $this->entityManager,
            $this->passwordEncoder,
            $this->notification,
            $this->mailGenerator,
        );
    }

    /**
     * Used to test forgotPassword method
     * 
     * 
     * @covers::forgotPassword
     */
    public function testForgotPassword()
    {
        $user = new User();
        $emailId = "demotest@gmail.com";
        $userRepository = \Mockery::mock(UserRepository::class);
        $this->entityManager->shouldReceive('getRepository')
            ->once()
            ->with(User::class)
            ->andReturn($userRepository);
        $userRepository->shouldReceive('findOneBy')
            ->once()
            ->with(['email' => $emailId])
            ->andReturn($user);
        $this->passwordEncoder->shouldReceive('encodePassword')
            ->once()
            ->with($user, "sdf514as10")
            ->andReturn("aadsdf5421211");
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with($user)
            ->andReturn(true);
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->mailGenerator->shouldReceive('sendMail')
            ->once()
            ->with([
                'name' => $user->getUserName(),
                'toEmail' => $emailId,
                'subject' => 'Temporary Password',
                'templateName' => 'emailTemplates/tempPasswordEmail.html.twig',
                'tempPassword' => 'sdf514as10',
            ])
            ->andReturn(true);
        $this->notification->shouldReceive('setNotification')
            ->once()
            ->with(
                'info', 
                'Your temporary password has been sent ot your email. Please check your email
             for temporary password.'
            );
        $response = $this->forgotPasswordHelper->forgotPassword($emailId);
        $this->assertTrue($response);
    }
}

namespace App\Service;

function uniqid() 
{
    return 12411;
}

function md5() 
{
    return "57a1db0211c961f470569ef5e2a9c5a0";
}

function substr() 
{
    return "sdf514as10";
}
