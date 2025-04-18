# Usar uma imagem base do PHP com extensões necessárias
FROM php:8.1-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar os arquivos do projeto
COPY . /var/www/html

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Criar o arquivo .env a partir do .env.example
RUN cp .env.example .env

# Gerar a chave da aplicação
RUN php artisan key:generate

# Expor a porta 8000
EXPOSE 8000

# Comando para iniciar o servidor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]