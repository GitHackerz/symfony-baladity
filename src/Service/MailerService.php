<?php

// src/Service/MailerService.php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerService extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $recipient, string $subject, string $body): void
    {
        $email = (new Email())
            ->from(new Address("no-reply@baladity.com", "Baladity"))
            ->to($recipient)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }

    public function sendTwigEmail(string $recipient, string $subject, string $template, array $context): void
    {
        $email = (new Email())
            ->from(new Address("no-reply@baladity.com", "Baladity"))
            ->to($recipient)
            ->subject($subject)
            ->html(
                $this->renderView(
                    $template,
                    $context
                )
            );

        $this->mailer->send($email);
    }
}