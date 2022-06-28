<?php

namespace App\Manager\Logger;

use PHPMailer\PHPMailer\PHPMailer;

class EmailLogger extends BaseLogger
{
    public const SEND = 'SEND CAR';

    /**
     * @param PHPMailer $email
     */
    public function emailSend(PHPMailer $email): void
    {
        //$this->logger->info(self::SET .': ', [$this->user->getName(), $this->user->getRoles()]);
        $this->logger->info(self::SEND . ': ');
        $this->logger->info($this->serializer->serialize($email, 'json'));
        $this->logger->info('');
    }
}
