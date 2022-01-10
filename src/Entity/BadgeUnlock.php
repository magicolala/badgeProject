<?php

namespace App\Entity;

use App\Repository\BadgeUnlockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgeUnlockRepository::class)]
class BadgeUnlock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Badge::class, inversedBy: 'unlocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Badge $badge;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'badgeUnlocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
