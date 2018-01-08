FROM php:7.1-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    wget

RUN wget https://getcomposer.org/composer.phar \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && composer config --global repo.packagist composer https://packagist.org

COPY default.conf /etc/apache2/sites-available/000-default.conf

RUN cd /var/www && git clone https://github.com/alexdebril/rss-atom-demo.git demo
RUN cd /var/www/demo && composer install
RUN mkdir -p /var/www/demo/var
RUN chown -R www-data:www-data /var/www/demo/var

CMD ["apache2-foreground"]
