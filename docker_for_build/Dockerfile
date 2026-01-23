FROM ubuntu:16.04

LABEL Description="MEICAN Image" \
      Author="Leonardo Lauryel Batista dos Santos"

ARG MEICAN_VERSION=v3.2.0

ENV DEBIAN_FRONTEND=noninteractive \
    DEBCONF_NONINTERACTIVE_SEEN=true

ENV MEICAN_DIR=/home/meican/meican

RUN apt-get update \
 && apt-get install -yq --no-install-recommends \
    apt-utils \
    ca-certificates \
    curl \
    sudo \
    zip \
    unzip \
    git \
    apache2 \
    php7.0 \
    php7.0-mysql \
    php7.0-mbstring \
    php7.0-curl \
    php7.0-soap \
    php7.0-xml \
    libapache2-mod-php \
 && update-ca-certificates \
 && rm -rf /var/lib/apt/lists/*

# Create user
RUN adduser --disabled-password --gecos '' meican \
 && usermod -aG sudo meican

# Clone MEICAN and checkout tag
RUN git clone https://github.com/ufrgs-hyman/meican.git /home/meican/meican \
 && cd /home/meican/meican \
 && git checkout ${MEICAN_VERSION} \
 && chown -R meican:meican /home/meican

WORKDIR /home/meican/meican

# Composer
RUN su -s /bin/bash meican -c "curl -fsSL https://getcomposer.org/download/1.9.3/composer.phar -o composer.phar" \
 && su -s /bin/bash meican -c "php composer.phar global require \"fxp/composer-asset-plugin:~1.4.4\"" \
 && ln -s /home/meican/meican/web /var/www/meican \
 && a2enmod rewrite

COPY 000-default.conf /etc/apache2/sites-available/

EXPOSE 80

CMD cp docker_for_build/db.php config/ \
 && chown meican:meican config/db.php \
 && su -s /bin/bash meican -c "php composer.phar install" \
 && service apache2 start \
 && /bin/bash

