FROM nginx:1.25.4-alpine

# Install certbot and nginx plugin
RUN apk add --no-cache certbot && \
    pip install certbot-nginx && \
    certbot --nginx -d bot7.ir -d www.bot7.ir && \
    certbot renew --dry-run




