# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Устанавливаем необходимые расширения PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Копируем файлы приложения
COPY . /var/www/html/

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html

# Включаем модуль rewrite для Apache
RUN a2enmod rewrite

# Экспонируем порт 80
EXPOSE 80