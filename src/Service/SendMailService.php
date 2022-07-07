<?php

namespace App\Service;

use App\Entity\Bill;
use App\Event\EmailEvent;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SendMailService
{
    private ParameterBagInterface $params;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    public function __construct(ParameterBagInterface $params, EventDispatcherInterface $dispatcher)
    {
        $this->params = $params;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws Exception
     */
    public function sendRegisterMail(string $email, string $subject): void
    {

        $mail = $this->zohoMailConfig();

        try {
            //Recipients
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = 'Welcome to our Website';
            $mail->send();

            $event = new EmailEvent($mail);
            $this->dispatcher->dispatch($event, EmailEvent::SEND);
        } catch (Exception $e) {
            throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    /**
     * @throws Exception
     */
    public function sendBillMail(string $email, string $subject, Bill $bill): void
    {

        $mail = $this->zohoMailConfig();

        try {
            //Recipients
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = 'This is your payment Total: ' . $bill->getTotalPrice();
            $mail->send();

            $event = new EmailEvent($mail);
            $this->dispatcher->dispatch($event, EmailEvent::SEND);
        } catch (Exception $e) {
            throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    /**
     * @throws Exception
     */
    private function zohoMailConfig(): PHPMailer
    {
        return $this->mailConfig(
            $this->params->get('zoho.mail.host'),
            $this->params->get('zoho.mail.username'),
            $this->params->get('zoho.mail.password'),
            $this->params->get('zoho.mail.port'),
        );
    }

    private function getEmailTemplate(Booking $booking): string
    {
        $now = new \DateTime();
        $now = $now->format('Y-m-d');
        $mailBody = file_get_contents($this->projectDir.'/templates/emailTemplate.html');
        $mailBody = str_replace('%now%', $now, $mailBody);
        $mailBody = str_replace('%hotelName%', $booking->getRoom()->getHotel()->getName(), $mailBody);
        $mailBody = str_replace('%name%', $booking->getFullName(), $mailBody);
        $mailBody = str_replace('%hotelDescription%', $booking->getRoom()->getHotel()->getDescription(), $mailBody);
        $mailBody = str_replace('%roomNumber%', $booking->getRoom()->getNumber(), $mailBody);
        $mailBody = str_replace('%checkin%', $booking->getCheckIn()->format('Y-m-d'), $mailBody);
        $mailBody = str_replace('%checkout%', $booking->getCheckOut()->format('Y-m-d'), $mailBody);
        $mailBody = str_replace('%total%', $booking->getTotal(), $mailBody);
        $mailBody = str_replace(
            '%imageHotel%',
            $booking->getRoom()->getHotel()->getHotelImages()->toArray()[0]->getImage()->getPath(),
            $mailBody
        );

        return $mailBody;
    }

    /**
     * @throws Exception
     */
    private function mailConfig(string $host, string $username, string $password, int $port): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $port;
        $mail->setFrom($username, 'Triply');

        return $mail;
    }
}
