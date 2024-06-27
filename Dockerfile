# Usar una imagen base oficial de PHP
FROM php:7.4-apache

# Copiar el contenido del directorio actual al directorio raíz del contenedor
COPY . /var/www/html/

# Instalar extensiones necesarias de PHP (ejemplo: pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Exponer el puerto 80 para el servidor web
EXPOSE 80

# Configurar el comando de inicio para Apache
CMD ["apache2-foreground"]
