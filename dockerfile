FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo_mysql

RUN a2enmod headers expires deflate rewrite

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# ENV APACHE_RUN_USER root
# ENV APACHE_RUN_GROUP root
# # RUN sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=root/g' /etc/apache2/envvars
# # RUN sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=root/g' /etc/apache2/envvars

WORKDIR /var/www/html
