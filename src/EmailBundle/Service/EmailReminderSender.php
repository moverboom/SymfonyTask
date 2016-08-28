<?php

namespace EmailBundle\Service;

use Swift_Message;
use AuthBundle\Entity\User;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class should probably be renamed since it is not a generic email sender
 *
 * Class EmailReminderSender
 * @package EmailBundle\Service
 */
class EmailReminderSender
{
    private $engine;
    private $mailer;

    public function __construct(TwigEngine $engine, \Swift_Mailer $mailer)
    {
        $this->engine = $engine;
        $this->mailer = $mailer;
    }

    public function sendEmailReminders(array $tasks) {
        foreach ($tasks as $task) {
            $sendTo = $task->getUser()->getEmail();
            $taskTitle = $task->getTitle();
            $taskDeadline = $task->getDeadline()->format('Y-m-d H:i');
            $message = $this->createEmailMessage($sendTo, $taskTitle, $taskDeadline);
            $this->mailer->send($message);
        }
    }

    private function createEmailMessage(string $to, string $taskTitle, string $taskDeadline) {
        $message = Swift_Message::newInstance()
            ->setSubject('You have an upcomming task today!')
            ->setFrom('mail@taskmanager.com')
            ->setTo($to)
            ->setBody(
                $this->engine->render(
                    'EmailBundle:templates/mails:mail_task_reminder.html.twig',
                    array(
                        'task_title' => $taskTitle,
                        'task_deadline' => $taskDeadline)
                ), 'text/html'
            );
        return $message;
    }
}
