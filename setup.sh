#!/bin/bash

# Stop and remove any existing containers (optional)
echo "Stopping and removing existing containers..."
docker-compose down

# Build and start Docker containers
echo "Building and starting Docker containers..."
docker-compose up -d --build

# Wait for PostgreSQL to be ready (can adjust the wait time as needed)
echo "Waiting for PostgreSQL to be ready..."
sleep 10

# Run database migrations
echo "Running database migrations..."
docker-compose exec app php artisan migrate --force

# Clear configuration cache (optional but recommended)
echo "Clearing configuration cache..."
docker-compose exec app php artisan config:clear

# Clear application cache (optional but recommended)
echo "Clearing application cache..."
docker-compose exec app php artisan cache:clear

# Clear route cache (optional but recommended)
echo "Clearing route cache..."
docker-compose exec app php artisan route:clear

# Optionally, you can seed the database (if you have seeders)
# echo "Seeding the database..."
# docker-compose exec app php artisan db:seed

# Verify the application is running
echo "Application is up and running. Access it at http://localhost:8000"
