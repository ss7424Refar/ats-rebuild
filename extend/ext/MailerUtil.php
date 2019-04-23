<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-12
 * Time: 上午9:13
 */

namespace ext;
use think\Loader;
Loader::import('lib.swift_required'); // 不要问我为什么，本尊也不造。
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
     * @param $cc
     * @param $mailTitle
     * @param $content
     * @return mixed
     */
    public static function send($to, $cc, $mailTitle, $content) {

        $transport = Swift_SmtpTransport::newInstance(config('smtp_host'), config('smtp_port'));

        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance($mailTitle)
            ->setFrom(array(config('mail_from')))
            ->setTo($to)
            ->setCc(json_decode($cc, true))
            ->setBody($content, 'text/html', 'utf-8');

        // Send the message
        $result = $mailer->send($message);
        return $result;

    }
}