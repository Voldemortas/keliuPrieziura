#!/bin/bash 
mysql -e "CREATE USER 'symf'@'localhost' IDENTIFIED BY 'ctN7Qgmx6pGGWjA3RVTnbDLv';GRANT ALL PRIVILEGES ON *.* TO 'symf'@'localhost';FLUSH PRIVILEGES;"