<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use App\Service\EmailService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class SendEmailMessageHandler implements MessageHandlerInterface {
    private $emailService;

    public function __construct(EmailService $emailService) {
        $this->emailService = $emailService;
    }

    public function __invoke(SendEmailMessage $message) {
        $this->emailService->sendGameSummaryEmail(
            $message->getRecipientEmail(),
            $message->getGameSummary()
        );
    }
}