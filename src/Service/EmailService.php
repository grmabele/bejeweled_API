<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService {
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function sendGameSummaryEmail($recipientEmail, $gameSummary) {
        $email = (new Email())
            ->from('your-email@example.com')
            ->to($recipientEmail)
            ->subject('Game Summary')
            ->text($gameSummary);

        $this->mailer->send($email);
    }
}
