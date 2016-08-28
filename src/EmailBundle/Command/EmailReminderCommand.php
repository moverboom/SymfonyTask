<?php

namespace EmailBundle\Command;

use AuthBundle\Entity\User;
use TaskBundle\Entity\Task;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * This command fetches all tasks for which a reminder needs to be send
 * and sends it to the user's email.
 * Called as -> email:send:reminders
 *
 * Class EmailReminderCommand
 * @package EmailBundle\Command
 */
class EmailReminderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('email:send:reminders')
            ->setDescription('Send email reminders')
            ->setHelp('Send email reminders to users about their upcoming tasks. This command should be run as cronjob');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sending email reminders...');
        $taskArray = $this->fetchRemindingTasks();
        $emailSender = $this->getContainer()->get('app.email.reminder.sender');
        $emailSender->sendEmailReminders($taskArray);
        $this->setTasksReminded($taskArray);
        $output->writeln('Done');
        return;
    }

    /**
     * Fetch all tasks for which a reminder needs to be send
     *
     * @return array
     */
    private function fetchRemindingTasks()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $repo = $doctrine->getRepository('TaskBundle:Task');
        $qb = $repo->createQueryBuilder('task');
        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('task.reminded', 0),
            $qb->expr()->lte('task.remindAt', ':currentDateTime')
        ))->setParameter('currentDateTime', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

        $tasks = $qb->getQuery()->getResult();

        return $tasks;
    }

    /**
     * Mark all tasks in array as reminded
     *
     * @param array $tasks
     */
    private function setTasksReminded(array $tasks) {
        $doctrine = $this->getContainer()->get('doctrine');
        foreach ($tasks as $task) {
            $task->setReminded(true);

        }
        $doctrine->getManager()->flush();
    }

}