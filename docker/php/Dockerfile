# PHP 7.3 FPMをベースに使用 
FROM php:7.3-fpm

# Nginxのインストール
RUN apt-get update && apt-get install -y nginx

# 必要なPHP拡張とツールのインストール 
RUN apt-get install -y zlib1g-dev libzip-dev mariadb-client vim \
    && docker-php-ext-install zip pdo_mysql

# Composerのインストール 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# Node.jsのインストール 
RUN curl -SL https://deb.nodesource.com/setup_20.x | bash
RUN apt-get install -y nodejs && npm install -g npm@latest

# Nginxの設定ファイルをコピー
# デフォルトの設定を消して、添付された default.conf を配置します
RUN rm /etc/nginx/sites-enabled/default
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# PHPの設定ファイルをコピー 
# パスを修正（php-fpmが読み込むディレクトリへ）
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# 作業ディレクトリの設定とソースコードのコピー 
# default.confのroot設定に合わせて /var/www/application に配置します
WORKDIR /var/www/application
COPY . .

# 権限の設定
RUN chown -R www-data:www-data /var/www/application

# NginxとPHP-FPMを両方起動するためのコマンド
# Renderのポートチェックを通すためにNginxをバックグラウンドにせず起動します
CMD service php7.3-fpm start && nginx -g "daemon off;"

RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini