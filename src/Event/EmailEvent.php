<?php

namespace App\Event;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Contracts\EventDispatcher\Event;

class EmailEvent extends Event
{
    public const SEND = 'email.send';

    /**
     * @var PHPMailer
     */
    private PHPMailer $email;

    /**
     * @param PHPMailer $email
     */
    public function __construct(PHPMailer $email)
    {
        $this->email = $email;
    }

    /**
     * @return PHPMailer
     */
    public function getEmail(): PHPMailer
    {
        return $this->email;
    }
}
