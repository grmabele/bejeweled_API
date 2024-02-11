<?php

namespace App\Message;

class SendEmailMessage {
    private $recipientEmail;
    private $gameSummary;

    public function __construct(string $recipientEmail, string $gameSummary) {
        $this->recipientEmail = $recipientEmail;
        $this->gameSummary = $gameSummary;
    }

    public function getRecipientEmail(): string {
        return $this->recipientEmail;
    }

    public function getGameSummary(): string {
        return $this->gameSummary;
    }
}