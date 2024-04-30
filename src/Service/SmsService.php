<?php

// src/Service/MailerService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class SmsService
{
    private $texter;

    public function __construct(TexterInterface $texter)
    {
        $this->texter = $texter;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSms(string $body): void
    {
        $recipient = '+21658906040';

        if (empty($body)) {
            throw new \InvalidArgumentException('Body cannot be empty');
        }

        $sms = new SmsMessage($recipient, $body);
        $this->texter->send($sms);
    }
}
