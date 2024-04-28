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
    public function sendSms(string $recipient, string $body): void
    {
        if (empty($recipient)) {
            throw new \InvalidArgumentException('Recipient cannot be empty');
        }
        if (empty($body)) {
            throw new \InvalidArgumentException('Body cannot be empty');
        }
        if (substr($recipient, 0, 4) !== '+216') {
            $recipient = '+216' . substr($recipient, 0);
        }
        var_dump($recipient, $body);
        $sms = new SmsMessage($recipient, $body);
        $this->texter->send($sms);
    }
}
