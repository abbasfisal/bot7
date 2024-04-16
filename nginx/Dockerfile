FROM nginx:1.25.4-alpine

RUN apk add --no-cache certbot python3-certbot-nginx && \
    certbot --nginx -d bot7.ir -d www.bot7.ir && \
    certbot renew --dry-run



