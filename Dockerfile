FROM php:8.4-apache

# Install ekstensi PDO MySQL/MariaDB
RUN docker-php-ext-install pdo_mysql

# Setting Apache biar ngarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Aktifkan mod_rewrite Apache (biar route Laravel jalan)
RUN a2enmod rewrite