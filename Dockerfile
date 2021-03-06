FROM ubuntu:18.04
ENV DEBIAN_FRONTEND noninteractive
ADD . /app
RUN apt-get update
RUN apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php7.4 apache2 git git-core curl zip unzip wget vim
RUN apt-get install -y php7.4-mysql php7.4-intl php7.4-curl php7.4-xml php7.4-zip
RUN update-alternatives --set php /usr/bin/php7.4
RUN apt-get install -y libapache2-mod-php
# Configure Apache
RUN rm -rf /var/www/* \
    && a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf
ADD vhost.conf /etc/apache2/sites-available/000-default.conf
# Install mysql
RUN apt-get install -y mysql-server
# Install Composer
RUN wget https://getcomposer.org/download/2.0.8/composer.phar
RUN chmod +x composer.phar
RUN echo alias composer='/composer.phar' >> ~/.bashrc
# Install Symfony
RUN mkdir -p /usr/local/bin
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN chmod a+x /root/.symfony/bin/symfony
RUN echo export PATH=\"\$HOME/.symfony/bin:\$PATH\" >> ~/.bashrc
RUN echo /etc/init.d/mysql start >> ~/.bashrc
RUN /etc/init.d/mysql start
# Set up mysql and other configs for the first rune
RUN echo 1 > /first_time
ADD mysql.sh /mysql.sh
RUN chmod +x /mysql.sh
RUN echo /bin/bash /mysql.sh >> ~/.bashrc
# Add main start script for when image launches
ADD run.sh /run.sh
RUN chmod 0755 /run.sh
WORKDIR /app
EXPOSE 8000
CMD ["/run.sh"]