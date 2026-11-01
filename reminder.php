<?php
/**
 * Daily Discord Reminder triggered by a CronJob.
 * Run from cron, e.g.:
 *   0 9 * * * /usr/bin/php /path/to/daily_reminder.php >> /path/to/reminder.log 2>&1
 * Written By HereticErik - https://github.com/hereticerik
 * For educational/demonstration purposes, use with caution and secure this page if you use it for something.
 */

// Only allow command-line execution so nobody can trigger it via the browser.
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Forbidden');
}

// ---------- CONFIG ----------
$webhookUrl = getenv('DISCORD_REMINDER_WEBHOOK') ?: 'https://discord.com/api/webhooks/xxx/zzz';

$title       = '⏰ Daily Reminder';
$description = "**Self Care is important:** Remember to Hydrate, Eat, Sleep, Shower, Brush Your Teeth and Floss, Practice Good Hygiene/Posture, and Take Care of Yourself.";
$color       = 0x702189; // Purple
$fields      = [
    // ['name' => 'Task 1', 'value' => 'Take your meds', 'inline' => true],
    // ['name' => 'Task 2', 'value' => 'Drink water',    'inline' => true],
];
$footerText  = 'This has been your Daily Reminder';
$mention     = ''; // e.g. '<@123456789012345678>' or '@everyone' to ping someone
// -----------------------------

$embed = [
    'title'       => $title,
    'description' => $description,
    'color'       => $color,
    'timestamp'   => gmdate('c'),
    'footer'      => ['text' => $footerText],
];
if (!empty($fields)) {
    $embed['fields'] = $fields;
}

$payload = [
    'content'          => $mention,
    'embeds'           => [$embed],
    'allowed_mentions' => ['parse' => $mention !== '' ? ['users', 'roles', 'everyone'] : []],
];

$ch = curl_init($webhookUrl);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
]);

$response = curl_exec($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

$time = date('Y-m-d H:i:s');
if ($error || $status < 200 || $status >= 300) {
    fwrite(STDERR, "[$time] Failed (HTTP $status): " . ($error ?: $response) . PHP_EOL);
    exit(1);
}

echo "[$time] Reminder sent (HTTP $status)" . PHP_EOL;
