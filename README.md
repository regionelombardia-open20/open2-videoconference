# Videoconference Plugin #

Plugin for E015 common modules.

## Installation

### 1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require open20/amos-videoconference
```

or add this row

```
"open20/amos-videoconference": "dev-master"
```

to the require section of your `composer.json` file.


### 2. Add module to your main config in common:
	
```php
<?php
'modules' => [
    'videoconference' => [
        'class' => 'open20\amos\videoconference\AmosVideoconference',
        'rbacEnabled' => false,
		'jitsiDomain' => 'jitsi-server.example.com',
    ],
],
```

Set **jitsiDomain** to the Jitsi server address.

### 3. Apply migrations

```bash
php yii migrate/up --migrationPath=@vendor/open20/amos-videoconference/src/migrations
```

or add this row to your migrations config in console:

```php
<?php
return [
    '@vendor/open20/amos-videoconference/src/migrations',
];
```

### 4. Configuration for sending email from console

insert in /console/component-others.php
```php
    'urlManager' => [
        'class' => 'yii\web\UrlManager',
    'baseUrl' => '/',
    'hostInfo' => 'http://example.org',
        // Disable index.php
        'showScriptName' => false,
        // Disable r= routes
        'enablePrettyUrl' => true,
        'rules' => array(
            '<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>] => <module>/<controller>/<action>',
        ),
    ],
```
change the paramater 'hostInfo' with the base url of your application, it is required for insert images inside the email template.

### 5. Console command for crons
The console command to launch the cron is:
```bash
php yii videoconference/cron/start_video_conference
```
```bash
php yii videoconference/cron/send_email_reminder
```


