FROM nginx

COPY static /app/static

RUN rm -rf /etc/nginx/conf.d/*.conf

COPY nginx.conf /etc/nginx/nginx.conf
COPY osu.conf /etc/nginx/conf.d/osu.conf