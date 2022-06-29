<?php

namespace App\Manager\Logger;

use PHPMailer\PHPMailer\PHPMailer;

class EmailLogger extends BaseLogger
{
    public const SEND = 'SEND EMAIL';

    /**
     * @param PHPMailer $mail
     */
    public function emailSend(PHPMailer $mail): void
    {
        $this->logger->info(self::SEND . ': ');
        $this->logger->info($this->serializer->serialize($mail, 'json'));
        $this->logger->info('');
    }
}
