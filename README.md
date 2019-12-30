# Server Audit Cron
This script will send a Git Status of all the virtual host in the server in you configured email ID.

You will have to set up a SMTP Setting in inc/config.php

## Setup
```
git clone https://github.com/prabhanjan/server-audit-cron.git
mv server-audit-cron AUDIT
cd AUDIT
composer install
```

## Config 
```
nano inc/config.php
```
```
      'Host' => 'email-smtp.us-east-1.amazonaws.com',
      'Username' => '',
      'Password' => '',
      'from' => '',
      'from_name' => ' Server Audit',
      'to' => '',
      'to_name' => '',
      'subject' => 'Git Status For server X',
```

## Test Run
```
php checkGitStatus.php
```


## Set Cron 
```
(crontab -l ; echo "0 0 * * * /usr/bin/php /root/AUDIT/checkGitStatus.php") | sort - | uniq - | crontab -
```
