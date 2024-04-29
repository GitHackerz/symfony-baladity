<?php

// src/Service/MailerService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

        $this->mailer = $mailer;
    }
    {
        $email = (new Email())
            ->subject($subject)
             $this->mailer->send($email);
    }
}
