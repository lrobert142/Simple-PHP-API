FROM php:7.2-apache

RUN apt-get update && apt-get install -y vim git zip unzip
RUN docker-php-ext-install pdo pdo_mysql mysqli

#Use mod_rewrite and allow it to be used
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
