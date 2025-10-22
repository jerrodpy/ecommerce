FROM nginx

ADD nginx.conf /etc/nginx/

ARG PHP_UPSTREAM_CONTAINER

RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:9000; }" > /etc/nginx/conf.d/upstream.conf

CMD ["nginx"]

EXPOSE 8080

