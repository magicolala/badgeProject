<?php

namespace App\Entity;

use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $action_name;

    #[ORM\Column(type: 'integer')]
    private $action_count;

    #[ORM\OneToMany(mappedBy: 'badge', targetEntity: BadgeUnlock::class)]
    private $unlocks;

    public function __construct()
    {
        $this->unlocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActionName(): ?string
    {
        return $this->action_name;
    }

    public function setActionName(string $action_name): self
    {
        $this->action_name = $action_name;

        return $this;
    }

    public function getActionCount(): ?int
    {
        return $this->action_count;
    }

    public function setActionCount(int $action_count): self
    {
        $this->action_count = $action_count;

        return $this;
    }

    /**
     * @return Collection|BadgeUnlock[]
     */
    public function getUnlocks(): Collection
    {
        return $this->unlocks;
    }

    public function addUnlock(BadgeUnlock $unlock): self
    {
        if (!$this->unlocks->contains($unlock)) {
            $this->unlocks[] = $unlock;
            $unlock->setBadge($this);
        }

        return $this;
    }

    public function removeUnlock(BadgeUnlock $unlock): self
    {
        if ($this->unlocks->removeElement($unlock)) {
            // set the owning side to null (unless already changed)
            if ($unlock->getBadge() === $this) {
                $unlock->setBadge(null);
            }
        }

        return $this;
    }
}
