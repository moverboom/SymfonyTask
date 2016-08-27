#! /bin/bash

#crontab settings: 0 * * * * /path/to/project/emailreminder.sh
cd "$(dirname "$0")"
php bin/console email:send:reminders
php bin/console swiftmailer:spool:send --env=dev >> var/logs/email_reminders.log
