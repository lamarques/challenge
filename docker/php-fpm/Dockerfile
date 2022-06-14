FROM php:8.1-fpm
WORKDIR "/application"

RUN apt-get update; \
    apt-get -y --no-install-recommends install git libbz2-dev libcurl4-openssl-dev libxml2-dev zlib1g-dev  \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev apt-utils libmcrypt-dev libicu-dev libssl-dev  \
    libxml2-dev libxslt-dev libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install bcmath bz2 calendar ctype curl dba dom exif fileinfo filter gd iconv intl mbstring mysqli pdo pdo_mysql opcache phar xsl zip soap session
RUN CFLAGS="-I/usr/src/php" docker-php-ext-install xml xmlreader xmlwriter simplexml

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


