<?php

namespace AppBundle\Command;

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

        $emailController = $this->getContainer()->get('app.email.controller');
        $emailController->sendAllEmailReminders();

        $output->writeln('Done');

        return;
    }
}