<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\Order;
use App\Entity\Tour;
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
    public function sendBillMail(string $email, string $subject, Bill $bill, Order $order, Tour $tour): void
    {

        $mail = $this->zohoMailConfig();
        try {
            //Recipients
            $mail->addAddress($email);
            $body = $this->getEmailTemplate($order, $bill, $tour);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
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

    private function getEmailTemplate(Order $order, Bill $bill, Tour $tour): string
    {
        $mailBody = file_get_contents(dirname(__DIR__, 2) . '/templates/email_invoice.html');
        $mailBody = str_replace('%tourImage%', 'https://car-rent-nhivo.s3.ap-southeast-1.amazonaws.com/upload/vinpearl-hotel-can-tho-62c906ac1e18f.jpg', $mailBody);
        $mailBody = str_replace('%orderPrice%', $order->getTotalPrice(), $mailBody);
        $mailBody = str_replace('%taxPrice%', $bill->getTax(), $mailBody);
        $mailBody = str_replace('%discountPrice%', $bill->getDiscount(), $mailBody);
        $mailBody = str_replace('%totalPrice%', $bill->getTotalPrice(), $mailBody);

        return $mailBody;
    }
}
