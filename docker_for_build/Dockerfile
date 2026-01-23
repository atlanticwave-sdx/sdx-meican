FROM ubuntu:16.04

LABEL Description="MEICAN Image" \
      Author="Leonardo Lauryel Batista dos Santos"

# Non-interactive APT
ENV DEBIAN_FRONTEND=noninteractive \
    DEBCONF_NONINTERACTIVE_SEEN=true

# Paths
ENV MEICAN_DIR=/home/meican/meican \
    COMPOSER_HOME=/home/meican/.composer

# -------------------------------------------------------------------
# Base system dependencies
# -------------------------------------------------------------------
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

# -------------------------------------------------------------------
# User
# -------------------------------------------------------------------
RUN adduser --disabled-password --gecos '' meican \
 && usermod -aG sudo meican \
 && mkdir -p /home/meican \
 && chown -R meican:meican /home/meican

# -------------------------------------------------------------------
# Application source (vendored, NOT git, NOT submodule)
# -------------------------------------------------------------------
COPY docker_for_build/meican /home/meican/meican
RUN chown -R meican:meican /home/meican

WORKDIR /home/meican/meican

# -------------------------------------------------------------------
# Composer 1 (required by Yii2 2.0.8 / yiisoft/yii2-composer 2.0.7)
# -------------------------------------------------------------------
RUN su -s /bin/bash meican -c "\
  curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php \
  && php /tmp/composer-setup.php --install-dir=/home/meican/meican --filename=composer.phar --1 \
  && php /home/meican/meican/composer.phar --version"

# Asset Packagist replaces fxp/composer-asset-plugin
RUN su -s /bin/bash meican -c "\
  HOME=/home/meican COMPOSER_HOME=/home/meican/.composer \
  php /home/meican/meican/composer.phar config -g repositories.asset-packagist composer https://asset-packagist.org"

# Install PHP dependencies
RUN su -s /bin/bash meican -c "\
  HOME=/home/meican COMPOSER_HOME=/home/meican/.composer \
  php /home/meican/meican/composer.phar install --no-interaction --no-progress --prefer-dist --no-scripts"

# -------------------------------------------------------------------
# Apache
# -------------------------------------------------------------------
RUN ln -s /home/meican/meican/web /var/www/meican \
 && a2enmod rewrite

COPY docker_for_build/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# -------------------------------------------------------------------
# Runtime
# -------------------------------------------------------------------
CMD ["bash", "-c", "service apache2 start && tail -f /var/log/apache2/access.log"]

