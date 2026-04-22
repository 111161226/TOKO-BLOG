FROM php:7.3-fpm

# Nginxのインストール
RUN apt-get update && apt-get install -y nginx

# 必要なPHP拡張のインストール
RUN apt-get install -y zlib1g-dev libzip-dev mariadb-client vim \
    && docker-php-ext-install zip pdo_mysql

# Composer / Node.js のインストール（既存のままでOK）
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -SL https://deb.nodesource.com/setup_20.x | bash \
    && apt-get install -y nodejs && npm install -g npm@latest

# --- 設定ファイルのコピー ---
# Nginx設定
RUN rm /etc/nginx/sites-enabled/default
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# PHP設定
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# --- ソースコードの配置 ---
# 1. コンテナ内の基準ディレクトリを作成
WORKDIR /var/www/application/src/application

# 2. プロジェクト全体をコピー
# これによりコンテナ内の /var/www/application/src/application/public/index.php に配置されます
COPY . /var/www/application/

# 3. 権限設定
RUN chown -R www-data:www-data /var/www/application

# 起動コマンド
CMD php-fpm -D && nginx -g "daemon off;"

RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini