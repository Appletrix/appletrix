# pego uma imagem já pronta com o PHP e Apache
FROM php:8.1-apache-buster

# variáveis de ambiente do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_LOG_DIR /var/log/apache2

# instala dependências de extensões, vim e supervisor
RUN apt-get update && apt-get install git libzip-dev vim supervisor -y

# instala as extensões
RUN docker-php-ext-install zip mysqli pdo pdo_mysql
RUN apt-get install sqlite3
# instala e configura o XDebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
EXPOSE 9003

ARG NODE_VERSION=16

RUN apt-get install -y php8.1-cli php8.1-dev \
       php8.1-pgsql php8.1-sqlite3 php8.1-gd \
       php8.1-curl \
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap \
       php8.1-intl php8.1-readline \
       php8.1-ldap \
       php8.1-msgpack php8.1-igbinary php8.1-redis php8.1-swoole \
       php8.1-memcached php8.1-pcov php8.1-xdebug \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /usr/share/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y mysql-client

# ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# cria o diretório de logs para o Supervisor
RUN mkdir /var/log/webhook

# copia os arquivos de configuração do Supervisor
COPY ./docker/supervisor/conf.d /etc/supervisor/conf.d

# copia o entreypoint
COPY ./docker/entrypoint /var/www/entrypoint

# adiciona permissão de execução para o Entrypoint
RUN chmod +x /var/www/entrypoint

# instala o Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
# RUN php composer-setup.php --install-dir=/usr/bin --filename=composer
# RUN php -r "unlink('composer-setup.php');"

# seta o document root configurado anteriormente nas variáveis de ambiente
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# copia o projeto para o Docker
COPY ./ /var/www/html
RUN composer install

# informa o diretório raiz do Docker
WORKDIR /var/www/html

# informa o Entrypoint
ENTRYPOINT [ "/var/www/entrypoint" ]
