#!/bin/sh
set -e

echo "Starting entrypoint script..."

# Copy Docker .env if no .env exists
if [ ! -f .env ]; then
    echo "No .env found. Copying .env.docker to .env..."
    cp .env.docker .env
else
    echo ".env file already exists. Ensuring it connects to Docker MySQL host 'db' is highly recommended."
fi

# Run Composer Install if vendor folder doesn't exist
if [ ! -d "vendor" ]; then
    echo "vendor/ directory not found. Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "vendor/ directory exists. Skipping composer install. (Run manually if packages changed)"
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64" .env; then
    echo "Generating app key..."
    php artisan key:generate --ansi
fi

# Wait for MySQL to become ready
echo "Checking database connection..."
until php -r "
try {
    \$db = new PDO('mysql:host=db;port=3306;dbname=zakat', 'zakat', 'secret');
    exit(0);
} catch (PDOException \$e) {
    exit(1);
}
" > /dev/null 2>&1; do
    echo "Database is not ready yet. Waiting..."
    sleep 3
done
echo "Database is ready!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Install and build frontend assets if node_modules doesn't exist or build is empty
if [ ! -d "node_modules" ] || [ ! -d "public/build" ]; then
    echo "node_modules/ or public/build/ not found. Installing NPM packages and building Vite assets..."
    npm install
    npm run build
else
    echo "node_modules/ and public/build/ already exist. Skipping asset build."
fi

# Clear configurations for development
echo "Clearing application cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start Supervisord to manage processes
echo "Starting Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
