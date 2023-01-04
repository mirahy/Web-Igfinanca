FROM wyveo/nginx-php-fpm:latest
WORKDIR /usr/share/nginx/
RUN rm -rf html
COPY . /usr/share/nginx
RUN chmod -R 777 /usr/share/nginx/storage/*
RUN ln -s public html
RUN rm /etc/nginx/conf.d/default.conf
RUN cp  /usr/share/nginx/default.conf  /etc/nginx/conf.d/

# WORKDIR /usr/share/nginx
# RUN git clone https://github.com/mirahy/Web-Igfinanca.git /usr/share/nginx/aux
# RUN cp -rf /usr/share/nginx/aux/. /usr/share/nginx
# RUN rm -rf /usr/share/nginx/aux
# RUN composer update
# RUN cp -r .env.example .env
# RUN php artisan key:generate
# RUN rm -rf /usr/share/nginx/html
# RUN ln -s public html
# RUN chmod -R 777 storage
# ENV DB_CONNECTION=adb_mtz
# ENV DB_HOST=mysql-app
# ENV DB_PORT=3306
# ENV DB_DATABASE=adb_mtz
# ENV DB_USERNAME=root
# ENV DB_PASSWORD=root
