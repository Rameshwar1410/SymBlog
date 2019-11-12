<?php
declare (strict_types = 1);

namespace App\Tests\Service;

use App\Service\MailGenerator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use PHPUnit\Framework\TestCase;

/**
 * Used to test MailGenerator service
 * 
 * @coversDefaultClass App\Service\MailGenerator
 */
class MailGeneratorTest extends TestCase
{
    /** @var Swift_Mailer $mailer An instance of Swift_Mailer  */
    private $mailer;

    /** @var EngineInterface $templating An instance of EngineInterface  */
    private $templating;

    /** @var MailGenerator $mailGenerator An instance of MailGenerator  */
    private $mailGenerator;

    public function setUp()
    {
        $this->templating = \Mockery::mock(EngineInterface::class);
        $this->mailer = \Mockery::mock(\Swift_Mailer::class);
        $this->mailGenerator = new MailGenerator($this->mailer, $this->templating);
    }

    /**
     * Used to test sendMail method
     * 
     * @covers::sendMail
     */
    public function testSendMail()
    {
        $swift_Message = \Mockery::mock(\Swift_Message::class);
        $this->templating->shouldReceive('render')
            ->once()
            ->with(
                'emailTemplates/registerEmail.html.twig',
                [
                    'name' => 'test',
                    'tempPassword' => '',
                ]
            )
            ->andReturnSelf();
        $this->mailer->shouldReceive('send')
            ->once()
            ->with(\Mockery::on(function ($swift_Message) {
                $this->assertInstanceOf(\Swift_Message::class, $swift_Message);
                
                return true;
            }))
            ->andReturn(true);
        $this->mailGenerator->sendMail([
            'name' => 'test',
            'toEmail' => 'ram.birajdar55@gmail.com',
            'subject' => 'Test mail',
            'templateName' => 'emailTemplates/registerEmail.html.twig',
        ]);
    }
}
