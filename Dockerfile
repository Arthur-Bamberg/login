# Dockerfile
FROM php:8.2-apache

# Instalar dependências necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Copiar o código da aplicação
WORKDIR /var/www/html

# Habilitar o módulo rewrite do Apache
RUN a2enmod rewrite

# Expor a porta padrão do Apache
EXPOSE 80
