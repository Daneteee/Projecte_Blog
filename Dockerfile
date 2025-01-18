# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Habilita el módulo mod_rewrite de Apache (útil para URLs amigables)
RUN a2enmod rewrite

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configura el directorio de trabajo de Apache
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80
