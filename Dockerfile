FROM php:8.3-cli-alpine AS test
WORKDIR /app
COPY src/ src/
COPY tests/ tests/
RUN php tests/shipping-test.php

FROM php:8.3-cli-alpine AS runtime
WORKDIR /app
COPY src/ src/
COPY public/ public/
ARG APP_VERSION=local
ENV APP_VERSION=$APP_VERSION
ENV PORT=10000
EXPOSE 10000
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public"]
