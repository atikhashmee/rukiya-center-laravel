FROM composer:2 as composer
WORKDIR /app

# copy composer files first for caching
COPY composer.json composer.lock ./
# Ensure bcmath is available when running composer (some packages require it)
# Install dependencies but skip Composer scripts for now (artisan isn't present yet)
RUN docker-php-ext-install bcmath \
 && composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

# copy the rest (application code) so artisan and other app files are available
COPY . .

# ensure autoload optimized (safe if vendor already present) and run post-install Artisan scripts
# Run package discovery now that `artisan` exists
RUN composer dump-autoload --optimize \
 && php artisan package:discover --ansi

# 2) Node build stage for React TypeScript frontend
FROM node:20-bullseye as node-builder
WORKDIR /app

# ensure Node 20 is used (Vite requires Node >=20.19 or 22.12)
# copy package files and install
COPY package*.json ./
RUN npm ci --no-audit --prefer-offline

# provide a php binary so `php artisan` can run during frontend build
# (some Vite plugins call artisan during build)
RUN apt-get update \
    && apt-get install -y --no-install-recommends php-cli git unzip \
    && rm -rf /var/lib/apt/lists/*

# copy composer-installed files from the composer stage so artisan and vendor autoload are available
COPY --from=composer /app /app

# copy application files and run the frontend build
COPY . .
# build script should produce assets into public/ (e.g. public/build or public/assets)
RUN npm run build

# 3) Final runtime image: php-fpm 8.4 + nginx (Alpine)
FROM php:8.4-fpm-alpine

# install nginx and utilities
RUN apk add --no-cache $PHPIZE_DEPS \
        nginx \
        bash \
        ca-certificates \
        unzip \
    && docker-php-ext-install bcmath \
    && apk del $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/*

# create webroot and copy built app
WORKDIR /var/www/html

# copy php app (vendor, app, routes, config, etc.) from composer stage
COPY --from=composer /app /var/www/html

# copy built frontend assets from node stage (overwrites public assets if any)
COPY --from=node-builder /app/public /var/www/html/public

# set permissions for Laravel storage and cache
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
  && chown -R www-data:www-data /var/www/html \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# nginx config: serve Laravel's public directory and pass PHP to php-fpm
RUN rm -f /etc/nginx/conf.d/default.conf
COPY <<'NGINX_CONF' /etc/nginx/conf.d/default.conf
server {
    listen 80;
    server_name _;

    root /var/www/html/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location ~* \.(?:css|js|jpg|jpeg|gif|png|ico|svg|woff2|woff|ttf|map)$ {
        expires 1y;
        add_header Cache-Control "public";
        try_files $uri =404;
    }

    client_max_body_size 100M;
}
NGINX_CONF

# configure php-fpm pool to listen on 127.0.0.1:9000 and run as www-data
RUN printf "[www]\nlisten = 127.0.0.1:9000\nuser = www-data\ngroup = www-data\n" > /usr/local/etc/php-fpm.d/zz-docker.conf

EXPOSE 80

# default command: start php-fpm in foreground and nginx (nginx in foreground)
CMD sh -c "php-fpm -F & nginx -g 'daemon off;'"
