FROM php:7.3-fpm
COPY php.ini /usr/local/etc/php/

# Nginx, PHP拡張のインストール
RUN apt-get update && apt-get install -y nginx zlib1g-dev libzip-dev mariadb-client vim \
    && docker-php-ext-install zip pdo_mysql

# Composerのインストール [cite: 1]
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

# 設定ファイルのコピー [cite: 2]
RUN rm /etc/nginx/sites-enabled/default
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# --- ここからが重要 ---

# 1. コンテナの作業ディレクトリを固定
WORKDIR /var/www/application

# 2. プロジェクトの「中身」をコンテナのルートにコピー
# こうすることで、コンテナの /var/www/application 直下に artisan や composer.json が来ます
COPY src/application/ . 

# 3. ライブラリのインストール
# これにより /var/www/application/vendor が作成されます
RUN composer install --no-dev --optimize-autoloader

# 4. 権限設定
RUN chown -R www-data:www-data /var/www/application
RUN chmod -R 775 /var/www/application/storage /var/www/application/bootstrap/cache

# 起動
CMD php-fpm -D && nginx -g "daemon off;"

RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini