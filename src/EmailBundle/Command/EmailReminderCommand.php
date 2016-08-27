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

        $userRepo = $this->getContainer()->get('doctrine')->getRepository('AuthBundle:User');

        $taskArray = $this->fetchUpcommingTasks();

        $emailSender = $this->getContainer()->get('app.email.sender');

        $emailSender->sendEmailReminders($taskArray);

        $output->writeln('Done');

        return;
    }

    private function fetchUpcommingTasks() {
        $doctrine = $this->getContainer()->get('doctrine');

        $datetimeEnd = new \DateTime();
        $datetimeEnd->add(new \DateInterval('P1D'));
        $datetimeStart = new \DateTime();

        $repo = $doctrine->getRepository('TaskBundle:Task');
        $qb = $repo->createQueryBuilder('t');

        $qb->where($qb->expr()->between('t.deadline', ':after', ':before'));
        $qb->setParameter('after', $datetimeStart, \Doctrine\DBAL\Types\Type::DATETIME);
        $qb->setParameter('before', $datetimeEnd, \Doctrine\DBAL\Types\Type::DATETIME);
        $query = $qb->getQuery();

        $tasks = $query->getResult();

        $resultArray = [];

        foreach ($tasks as $task) {
            $resultArray[$task->getUser()->getEmail()][] = ['task_title' => $task->getTitle(), 'task_deadline' => $task->getDeadline()->format('H:i')];
        }

        return $resultArray;
    }
}