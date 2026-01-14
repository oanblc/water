FROM php:8.2-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Create upload directories
RUN mkdir -p assets/images/products assets/images/uploads && \
    chmod -R 755 assets/images

# Expose port
EXPOSE 8080

# Start PHP built-in server with router
CMD php -S 0.0.0.0:${PORT:-8080} router.php
