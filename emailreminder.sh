#! /bin/bash

php bin/console email:send:reminders
php bin/console swiftmailer:spool:send --env=dev
