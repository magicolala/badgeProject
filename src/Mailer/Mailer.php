<?php

namespace App\Mailer;

use App\Entity\Badge;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function badgeUnlocked(Badge $badge, User $user)
    {
        $email = (new TemplatedEmail())
            ->from('noreply@example.com')
            ->to($user->getEmail())
            ->subject('Vous avez débloqué le badge ' . $badge->getName())
            ->htmlTemplate('emails/badge.html.twig')
            ->context([
                'badge' => $badge,
                'user' => $user
            ]);

        $this->mailer->send($email);
    }
}