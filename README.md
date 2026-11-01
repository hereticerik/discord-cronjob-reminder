# A simple php script that can be triggered with a cronjob to send a reminder as an embedded post to a discord channel with a webhook

**About This Project:** This is php file that can be used with cron jobs to send a daily/weekly/monthly reminder as an embedded post to a discord channel with a webhook. Do not put this php file on your web server where it's publicly accessible.

**This is released for educational/demonstration purposes, please use with caution and secure this web form if you use it for for your project or discord server.**


# Installation/Use 

To use, replace this webhook example with your discord webhook for the channel you'd like it to post the reminder to.
```
$webhookUrl = getenv('DISCORD_REMINDER_WEBHOOK') ?: 'https://discord.com/api/webhooks/xxx/zzz';

then configure the contents of your embedded webhook reminder post...

$title       = '⏰ Daily Reminder';
$description = "Don't forget to do your daily tasks!";
$color       = 0x702189; // Purple
$fields      = [
    // ['name' => 'Task 1', 'value' => 'Take your meds', 'inline' => true],
    // ['name' => 'Task 2', 'value' => 'Drink water',    'inline' => true],
];
$footerText  = 'This has been your Daily Reminder';
```

and setup a cronjob on your hosting/server to trigger this reminder whenever you want it to regularly be posted to a discord channel. Your web hosting provider may have a different system or web interface for cronjobs, check to see if they offer cronjobs as a feature.


```
0 9 * * * /usr/bin/php /path/to/daily_reminder.php >> /path/to/reminder.log 2>&1
```

Then upload the form to a web host that supports PHP 7.4 (or above preferably) with the cURL extension.




## Screenshots

![App Screenshot](https://github.heretic.network/screenshots/cronjob-reminder.png)

