#!/bin/bash
chgrp -R www-data .
source /etc/apache2/envvars
tail -F /var/log/apache2/* &
exec apache2 -D FOREGROUND