<?php

namespace App\Event;

use App\Entity\Badge;
use App\Entity\BadgeUnlock;
use App\Entity\User;
use JetBrains\PhpStorm\Pure;
use Symfony\Contracts\EventDispatcher\Event;

class BadgeUnlockedEvent extends Event
{
    const NAME = "badge.unlock";

    /**
     * @var BadgeUnlock
     */
    private BadgeUnlock $badgeUnlock;

    public function __construct(BadgeUnlock $badgeUnlock)
    {
        $this->badgeUnlock = $badgeUnlock;
    }

    /**
     * @return BadgeUnlock
     */
    public function getBadgeUnlock(): BadgeUnlock
    {
        return $this->badgeUnlock;
    }

    /**
     * @return Badge
     */
    #[Pure] public function getBadge(): Badge
    {
        return $this->badgeUnlock->getBadge();
    }

    /**
     * @return User
     */
    #[Pure] public function getUser(): User
    {
        return $this->badgeUnlock->getUser();
    }
}