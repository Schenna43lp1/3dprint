FROM php:8.3-apache

# Apache-Module für die .htaccess der Website
RUN a2enmod rewrite headers expires deflate

# AllowOverride All, damit .htaccess wirksam ist + ServerName setzen
RUN sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && printf '\nServerName localhost\n' >> /etc/apache2/apache2.conf

# PHP-Limits passend zum Upload-Formular (50 MB Dateien)
RUN { \
      echo 'upload_max_filesize=52M'; \
      echo 'post_max_size=55M'; \
      echo 'max_execution_time=60'; \
      echo 'display_errors=Off'; \
      echo 'log_errors=On'; \
    } > /usr/local/etc/php/conf.d/zz-app.ini

# Website ins Document-Root kopieren
COPY 3ddruck-suedtirol/ /var/www/html/

# uploads/ beschreibbar für den Webserver
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod 750 /var/www/html/uploads

EXPOSE 80
