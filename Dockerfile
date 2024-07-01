# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite

# Copiar el contenido de tu aplicación al directorio root del servidor Apache
COPY . /var/www/html/


# Instalar las extensiones de PHP necesarias (ajusta según las necesidades de tu app)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Exponer el puerto 80
EXPOSE 80
