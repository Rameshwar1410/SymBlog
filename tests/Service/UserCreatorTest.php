<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\{UserCreator, FileUploader};
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;;

/**
 * Used to test UserCreator service
 * 
 * @coversDefaultClass App\Service\UserCreator
 */
class UserCreatorTest extends TestCase
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface  */
    private $entityManager;

    /** @var UserPasswordEncoderInterface $passwordEncoder An instance of UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var UserCreator $userCreator An instance of UserCreator  */
    private $userCreator;

    /** @var FileUploader $fileUploader An instance of FileUploader  */
    private $fileUploader;

    public function setUp(): void
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->passwordEncoder = \Mockery::mock(UserPasswordEncoderInterface::class);
        $this->fileUploader = \Mockery::mock(FileUploader::class);
        $this->userCreator = new UserCreator(
            $this->entityManager,
            $this->passwordEncoder,
            $this->fileUploader
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
        $user->setFirstName('aa');
        $user->setImage('12345');
        $user->setLastName('142141');
        $user->setUserName('test');
        $uploadedFile = \Mockery::mock(UploadedFile::class);
        $this->passwordEncoder->shouldReceive('encodePassword')
            ->once()
            ->with($user, '111212221')
            ->andReturn('jfklsdj;ksd;');
        $this->fileUploader->shouldReceive('profileImage')
            ->once()
            ->with(
                $uploadedFile,
                '/images'
            )
            ->andReturn('filename');
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with($user)
            ->andReturn(true);
        $this->entityManager->shouldReceive('flush')
            ->once()
            ->withNoArgs();
        $this->userCreator->add(
            $user,
            '/images',
            $uploadedFile
        );
        $this->assertEquals('jfklsdj;ksd;', $user->getPassword());
    }
}
