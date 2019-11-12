<?php
declare (strict_types = 1);

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * MailGenerator service which is used for sending mail
 */
class MailGenerator
{
    /** @var Swift_Mailer $mailer An instance of Swift_Mailer */
    private $mailer;

    /** @var EngineInterface $templating An instance of EngineInterface */
    private $templating;

    /**
     * Constructor to initialize variable
     *
     * @param Swift_Mailer $mailer An instance of Swift_Mailer
     * @param EngineInterface $templating An instance of EngineInterface
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Send Email
     *
     * @param array $emailData Email info like name, toEmail, subject, templateName, and tempPassword(optional)
     * @return bool If success true otherwise false
     */
    public function sendMail(array $emailData): bool
    {
        $message = (new \Swift_Message)->setSubject($emailData['subject'])->setFrom(['rambirajdar1414@gmail.com' => 'Rameshwar'])
            ->setTo($emailData['toEmail'])->setBody($this
                ->templating
                ->render(
                    $emailData['templateName'],
                    [
                        'name' => $emailData['name'],
                        'tempPassword' => $emailData['tempPassword'] ?? '',
                    ]),
            'text/html',
        );
        if ($this->mailer->send($message)) {

            return true;
        } else {

            return false;
        }
    }
}
