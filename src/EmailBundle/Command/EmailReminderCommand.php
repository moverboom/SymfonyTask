<?php

namespace EmailBundle\Command;

use AuthBundle\Entity\User;
use TaskBundle\Entity\Task;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

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
        $emailSender = $this->getContainer()->get('app.email.sender');
        $emailSender->sendEmailReminders($taskArray);
        $output->writeln('Done');
        return;
    }

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

}