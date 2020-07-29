FROM php:7-apache
MAINTAINER sfernandez@ironbox.com.ar

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY start-apache /usr/local/bin
RUN a2enmod rewrite

# Copy application source
COPY VulnerableApp/ /var/www/html
RUN chown -R www-data:www-data /var/www/html

CMD ["start-apache"]
