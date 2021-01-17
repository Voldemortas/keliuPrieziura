#!/bin/bash 
if [ $(cat /first_time) -eq 1 ]
then
  echo 'It might take few seconds, please wait'
  echo 0 > /first_time
  mysql -e "CREATE USER 'symf'@'localhost' IDENTIFIED BY 'ctN7Qgmx6pGGWjA3RVTnbDLv';GRANT ALL PRIVILEGES ON *.* TO 'symf'@'localhost';FLUSH PRIVILEGES;"
  cd keliuPrieziura; composer install; php bin/console doctrine:database:create --if-not-exists;php bin/console doctrine:schema:drop  --force;php bin/console doctrine:schema:create;php bin/console doctrine:fixtures:load --append;php bin/console doctrine:migrations:migrate --no-interaction
fi