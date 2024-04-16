FROM nginx:1.25.4-alpine

RUN apt install python3-certbot-nginx \
    && apt certbot --nginx -d bot7.ir -d www.bot7.ir \
    && certbot renew --dry-run



