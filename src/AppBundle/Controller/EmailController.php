<?php

namespace AppBundle\Controller;

use Swift_Message;
use Symfony\Bundle\TwigBundle\TwigEngine;

class EmailController
{
    private $engine;
    private $mailer;

    public function __construct(TwigEngine $engine, \Swift_Mailer $mailer)
    {
        $this->engine = $engine;
        $this->mailer = $mailer;
    }

    public function sendAllEmailReminders() {
        $this->sendTestEmail();
    }

    private function sendTestEmail() {
        $message = Swift_Message::newInstance()
            ->setSubject('Test Reminder')
            ->setFrom('mail@taskmanager.com')
            ->setTo('matthijsske@gmail.com')
            ->setBody(
                $this->engine->render(
                    'templates/mails/mail_task_reminder.html.twig'
                ), 'text/html'
            );
        $this->mailer->send($message);
    }
}
