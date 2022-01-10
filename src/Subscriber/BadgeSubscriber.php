<?php

namespace App\Subscriber;

use App\Event\BadgeUnlockedEvent;
use App\Mailer\Mailer;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class BadgeSubscriber implements EventSubscriberInterface {


    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
         $this->mailer = $mailer;
    }

    #[ArrayShape([BadgeUnlockedEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            BadgeUnlockedEvent::NAME => "onBadgeUnlock"
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onBadgeUnlock(BadgeUnlockedEvent $event) {
        $this->mailer->badgeUnlocked($event->getBadge(), $event->getUser());
    }
}