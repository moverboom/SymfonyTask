<?php

namespace EmailBundle\Service;

use Swift_Message;
use AuthBundle\Entity\User;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class should probably be renamed since it is not a generic email sender
 *
 * Class EmailSender
 * @package EmailBundle\Service
 */
class EmailSender
{
    private $engine;
    private $mailer;

    public function __construct(TwigEngine $engine, \Swift_Mailer $mailer)
    {
        $this->engine = $engine;
        $this->mailer = $mailer;
    }

    public function sendEmailReminders(array $userTasks) {
        $userKeys = array_keys($userTasks);
        foreach ($userKeys as $user) {
            $sendTo = $user;
            $tasks = $userTasks[$user];
            foreach ($tasks as $task) {
                $taskTitle = $task['task_title'];
                $taskDeadline = $task['task_deadline'];
                $message = $this->createEmailMessage($sendTo, $taskTitle, $taskDeadline);
                $this->mailer->send($message);
            }
        }
    }

    private function createEmailMessage($to, $taskTitle, $taskDeadline) {
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
