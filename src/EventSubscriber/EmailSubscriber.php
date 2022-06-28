<?php

namespace App\EventSubscriber;

use App\Event\EmailEvent;
use App\Manager\Logger\EmailLogger;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailSubscriber implements EventSubscriberInterface
{
    private EmailLogger $emailLogger;

    public function __construct(EmailLogger $emailLogger)
    {
        $this->emailLogger = $emailLogger;
    }

    #[ArrayShape(['email.send' => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            'email.send' => 'onSendMail',
        ];
    }

    /**
     * @param EmailEvent $event
     * @return void
     */
    public function onSendEmail(EmailEvent $event): void
    {
        $this->emailLogger->emailSend($event->getEmail());
    }
}
