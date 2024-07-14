FROM nginx:alpine

COPY nginx.conf /etc/nginx/

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && \
    rm /etc/nginx/conf.d/default.conf && exit 0 ; exit 1

CMD ["nginx"]

EXPOSE 80 443
