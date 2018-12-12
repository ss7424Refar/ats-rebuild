<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-12
 * Time: 上午9:13
 */

namespace ext;
use think\Loader;
Loader::import('lib.swift_required');
//require_once '../../vendor/swiftmailer/swiftmailer/lib/swift_required.php';
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

/**
 * Class SendMailer
 * @package ext
 */
class MailerUtil {
    /**
     * @param $to
     * @param $subject
     * @param $mailTitle
     * @param $content
     * @return mixed
     */
    public static function send($to, $mailTitle, $content) {

        $transport = Swift_SmtpTransport::newInstance(config('SMTP_HOST'), config('SMTP_PORT1'));

        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance($mailTitle)
            ->setFrom(array(config('Mail_FROM')))
            ->setTo($to)
            ->setCc(json_decode(config('MAIL_CC'), true))
            ->setBody($content, 'text/html', 'utf-8');

        // Send the message
        $result = $mailer->send($message);
        return $result;

    }
}