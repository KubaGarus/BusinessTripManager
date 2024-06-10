# Użyj oficjalnego obrazu PHP z Apache
FROM php:8.0-apache

# Skopiuj pliki projektu do katalogu roboczego
COPY . /var/www/html/

# Skopiuj plik konfiguracyjny Apache
COPY ./.htaccess /var/www/html/.htaccess

# Ustawienie odpowiednich uprawnień
RUN chown -R www-data:www-data /var/www/html

# Włącz moduł rewrite Apache
RUN a2enmod rewrite
