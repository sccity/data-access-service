FROM --platform=linux/x86_64 php:8.3-fpm
RUN apt-get update && apt-get install -y \
    nginx \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring zip pdo pdo_mysql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
&& apt-get install -y nodejs

RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "error_reporting = E_ALL" > /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "display_errors = Off" >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php/conf.d/error-logging.ini

WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/html

RUN mkdir -p /var/www/html/storage/app/public/activity_files \
    && mkdir -p /var/www/html/storage/framework/{cache,sessions,views} \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/bootstrap/cache

COPY nginx.conf /etc/nginx/sites-available/default
RUN rm /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

#RUN npm ci || (rm -rf node_modules package-lock.json && npm install)

#COPY clean.sh /clean.sh
#RUN chmod +x /clean.sh
#RUN ./clean.sh

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

ENTRYPOINT ["/start.sh"]
