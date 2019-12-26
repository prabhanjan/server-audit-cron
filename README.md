# Server Audit Cron
This script will send a Git Status of all the virtual host in the server in you configured email ID.

You will have to set up a SMTP Setting in inc/config.php

## Set Cron 
```
(crontab -l ; echo "0 0 * * * /usr/bin/php /root/AUDIT/checkGitStatus.php") | sort - | uniq - | crontab -
```
