FROM php:8.3-fpm-alpine
ARG workdir=/var/www
WORKDIR $workdir

# Install system dependencies
RUN apk update 
RUN apk add --no-cache \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    zip \
    bash \
    dos2unix \
    mariadb-client \
    linux-headers \
    $PHPIZE_DEPS

# Install Xdebug
# RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug

# Configure Xdebug
# RUN { \
#         echo 'xdebug.mode=debug'; \
#         echo 'xdebug.start_with_request=yes'; \
#         echo 'xdebug.client_host=host.docker.internal'; \
#         echo 'xdebug.client_port=9003'; \
#         echo 'xdebug.log=/var/www/html/xdebug.log'; \
#     } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis
# Install various PHP extensions
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli 
RUN docker-php-ext-install exif 
RUN docker-php-ext-install zip 
RUN docker-php-ext-configure gd --with-freetype --with-jpeg 
RUN docker-php-ext-install -j$(nproc) gd


# Copy configuration files and scripts
COPY php.ini /usr/local/etc/php/
COPY . .

# Install Composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy and set permissions for the startup script
COPY docker-start.sh /var/www/
RUN chmod +x /var/www/docker-start.sh

# Create and switch to a less privileged user
RUN addgroup -g 1000 appuser && \
    adduser -D -u 1000 -G appuser appuser
RUN chown -R appuser:appuser /var/www
RUN chmod 644 /var/www/.env

USER appuser
CMD ["/var/www/docker/php/docker-start.sh"]