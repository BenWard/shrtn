FROM php:7.4-fpm

# Install unix dependencies
RUN apt-get update
RUN apt-get -y --no-install-recommends install curl libcurl3-dev libyaml-dev

RUN pecl install yaml
RUN docker-php-ext-enable yaml

# Clean up
RUN apt-get clean;
RUN rm -rf /var/lib/apt/lists/*
RUN rm -rf /tmp/*
RUN rm -rf /var/tmp/*
RUN rm -rf /usr/share/doc/*

WORKDIR "/var/shrtn/"
