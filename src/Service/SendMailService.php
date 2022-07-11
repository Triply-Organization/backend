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
    public function sendBillMail(
        string $subject,
        Bill   $bill,
        array  $data,
        string $phone,
        Order  $order,
        Tour   $tour,
    ): void {
        $mail = $this->zohoMailConfig();

        try {
            //Recipients
            $mail->addAddress($data['email']);
            $body = $this->getEmailTemplate($bill, $data, $phone, $order, $tour);
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

    private function getEmailTemplate(
        Bill  $bill,
        array $data,
        string $phone,
        Order $order,
        Tour  $tour
    ): string {
        $mailBody = file_get_contents(dirname(__DIR__, 2) . '/templates/emailBill.html');
        $mailBody = str_replace('%orderId%', $order->getId(), $mailBody);
        $mailBody = str_replace('%name%', $data['name'], $mailBody);
        $mailBody = str_replace('%phone%', $phone, $mailBody);
        $mailBody = str_replace('%tourImage%',$this->params->get('s3url') .
            $tour->getTourImages()[0]->getImage()->getPath(), $mailBody);
        $mailBody = str_replace('%email%', $data['email'], $mailBody);
        $mailBody = str_replace('%tourTitle%', $tour->getTitle(), $mailBody);
        $mailBody = str_replace('%totalBill%', $bill->getTotalPrice(), $mailBody);


        return $mailBody;
    }
}
