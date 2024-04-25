<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    public function __construct(private MailerInterface $mailer) {

        $this->mailer = $mailer;
    }
    public function sendEmail(
        $content = '',
        $subject = 'Nouvelle Demande d association'
    ): void
    {
        $email = (new Email())
            ->from('sarraabben432@gmail.com')
            ->to('Sarra.Abben@esprit.tn')
            ->subject($subject)
            ->html($content);
             $this->mailer->send($email);

    }
}