<?php
declare (strict_types = 1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * NotificationService service which is used for flash messages
 */
class NotificationGenerator
{
    /**
     * @var SessionInterface $session An instance of SessionInterface.
     */
    private $session;

    /**
     * NotificationGenerator constructor.
     *
     * @param SessionInterface $session An instance of SessionInterface
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Set Notification in Flash
     *
     * @param string $type Notification type (eg info, warning)
     * @param string $message Notification message
     */
    public function setNotification(string $type, string $message): void
    {
        $this
            ->session
            ->getFlashBag()
            ->add($type, $message);
    }
}
