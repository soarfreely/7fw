FROM 39.100.108.229/library/nginx16phpfpm73:1.0

COPY src /var/www/html
COPY docker/nginx-conf/nginx.conf /etc/nginx
COPY docker/nginx-conf/nginx.conf /etc/nginx/sites-enabled
COPY docker/fpm-conf/www.conf /usr/local/etc/php-fpm.d

# 设置时区
RUN ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
RUN echo 'Asia/Shanghai' >/etc/timezone

#RUN rm /bin/sh && \
#    ln -s /bin/bash /bin/sh && \
#    cd /data/www && \
#    composer install --no-dev -vvv && \
    #composer update && \
#    chown -R cy-crs:cy-crs /data/www
    #php artisan migrate

#VOLUME /home/soar/Desktop/storage/logs

#COPY dockerfiles/php-fpm.conf /usr/local/etc

# docker pull 39.100.108.229/library/nginx16phpfpm73:1.0

#http://39.100.108.229/harbor/users
#
#admin
#ali229-Harbor
#
#soar
#soarfreely123H
#
#推送镜像的Docker命令
#在项目中标记镜像：
#docker tag SOURCE_IMAGE[:TAG] 39.100.108.229/library/IMAGE[:TAG]
#
#推送镜像到当前项目：
#docker push 39.100.108.229/library/IMAGE[:TAG]