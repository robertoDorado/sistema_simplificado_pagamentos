#  PHP Drivers
FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Definir o diretório de trabalho
WORKDIR /var/www/html/

# Copia os arquivos do projeto para o diretório de trabalho
COPY . /var/www/html

# Instalação das dependências necessárias
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    dos2unix \
    ca-certificates && \
    update-ca-certificates \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql mysqli soap \
    && rm -rf /var/lib/apt/lists/*

# Copiar certificados SSL para o conteiner php-apache
COPY ssl/localhost.pem /etc/ssl/certs/localhost.pem
COPY ssl/localhost-key.pem /etc/ssl/private/localhost-key.pem

# Copiar arquivos de configuração
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY apache-config/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Atualização das configurações do Apache para usar SSL
RUN a2enmod ssl
RUN a2enmod socache_shmcb
RUN a2ensite default-ssl
RUN a2ensite 000-default

# Reiniciar o Apache
RUN service apache2 restart

# Definir permissões
RUN chown -R www-data:www-data /var/www/html/

# Instalação do Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Ativar o módulo rewrite
RUN a2enmod rewrite

# Permissão de super usuário para o composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Intalação do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer