<?php

namespace App\Service;

use App\Entity\BadgeUnlock;
use App\Entity\User;
use App\Event\BadgeUnlockedEvent;
use App\Mailer\Mailer;
use App\Subscriber\BadgeSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ObjectManager;
use Psr\EventDispatcher\EventDispatcherInterface;

class BadgeManager
{
    private ObjectManager $objectManager;
    private EventDispatcherInterface $eventDispatcher;
    private Mailer $mailer;

    public function __construct(EntityManagerInterface $objectManager, EventDispatcherInterface $eventDispatcher, Mailer $mailer)
    {
        $this->objectManager = $objectManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailer = $mailer;
    }

    /**
     * Check if a badge exists for this action and action occurence and unlock it for the user
     *
     * @param User $user
     * @param string $action
     * @param int $action_count
     * @return void
     * @throws NoResultException
     */
    public function checkAndUnlock(User $user, string $action, int $action_count): void
    {
        try {
            // Vérifier si on a un badge qui correspond à action et à action_count
            $badge = $this->objectManager->getRepository("App:Badge")->findWithUnlockForAction($user->getId(), $action, $action_count);

            if ($badge->getUnlocks()->isEmpty()) {
                $unlock = new BadgeUnlock();
                $unlock->setBadge($badge);
                $unlock->setUser($user);
                $this->objectManager->persist($unlock);
                $this->objectManager->flush();
                $this->eventDispatcher->addSubscriber(new BadgeSubscriber($this->mailer));
                $this->eventDispatcher->dispatch(new BadgeUnlockedEvent($unlock), BadgeUnlockedEvent::NAME);
            }

        } catch (NoResultException $e) {
            throw new NoResultException("Aucun résultat");
        }
    }
}