FROM php:8.2-apache

# ติดตั้ง mysqli และเปิด mod_rewrite
RUN docker-php-ext-install mysqli && a2enmod rewrite

# ตั้งค่า DirectoryIndex
RUN echo "DirectoryIndex index.php index.html" > /etc/apache2/conf-available/directoryindex.conf     && a2enconf directoryindex

WORKDIR /var/www/html
COPY . /var/www/html