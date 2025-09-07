# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Включаем необходимые модули Apache
RUN a2enmod rewrite

# Устанавливаем расширение PHP для MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Копируем все файлы проекта в контейнер
COPY . /var/www/html/

# Указываем рабочую директорию
WORKDIR /var/www/html/

# Открываем порт 80
EXPOSE 80

# Запускаем Apache в foreground режиме
CMD ["apache2-foreground"]