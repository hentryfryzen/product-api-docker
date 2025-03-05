# Laravel + PHP-FPM + PostgreSQL + Nginx Docker Setup

## Overview
This project sets up a Laravel application with PHP 8.2-FPM, PostgreSQL, and Nginx using Docker.

## Prerequisites
Ensure you have the following installed:
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Project Setup

### 1. Clone the Repository
```bash
git clone <your-repository-url>
cd <your-project-folder>
```

### 2. Create `.env` File
Copy the `.env.example` file to `.env` and configure database and application settings:
```bash
cp .env.example .env
```
--Note--
```bash
Docker Desktop File Sharing Setup
Step-by-Step Guide to Set Up File Sharing in Docker Desktop
1. Open Docker Desktop

    Launch Docker Desktop from your applications menu.

2. Go to Settings

    In the Docker Desktop window, click on the gear icon (⚙️) in the top-right corner to open Settings.

3. Navigate to Resources

    In the left-hand sidebar of the Settings window, click on Resources.

4. Select File Sharing

    Under Resources, select the File Sharing tab. This section allows you to specify which directories on your local system can be shared with Docker containers.

5. Add a Directory to Share

    In the File Sharing section, you'll see a list of directories that are currently shared. To add a new directory:
        Click the + (Add) button.
        Navigate to the directory you want to share (e.g., your project folder), and select it.
        Click Open or Add to confirm the selection.

6. Apply the Changes

    After adding the directory, click the Apply & Restart button at the bottom-right of the Settings window to apply the changes.
    Docker Desktop will restart to apply the new file sharing settings.

7. Verify the Shared Directory

    After Docker restarts, you can verify that the directory is shared by checking the Virtual File Shares list. The directory you added should now appear in the list.

Summary:

By following these steps, you enable Docker Desktop to share a specific directory (like your project folder) between your local machine and Docker containers. This makes it easier to access and modify files inside the container from your local system.

```
### 3. Set Up and Run the Project
Make the `setup.sh` script executable and run it:
```bash
chmod +x setup.sh
./setup.sh
```

### 4. Access the Application
Once the containers are running, you can access your Laravel application:
- **App URL:** `http://localhost:8001`

## Docker Services
The following services are included:
- **PHP 8.2-FPM** (Backend processing)
- **PostgreSQL** (Database)
- **Nginx** (Webserver)

## Managing the Containers
### Stop Containers
```bash
docker-compose down
```

### Restart Containers
```bash
docker-compose up -d
```

### View Running Containers
```bash
docker ps
```
## Import Products with Price Adjustments

To adjust product prices from a JSON file, use the following Artisan command:

```bash
php artisan import:adjust-prices <adjustment_percentage> <path_to_products_json_file>
```
Example:
```bash
php artisan import:adjust-prices 10 /Applications/MAMP/htdocs/Hentry/product-api/products.json
```
This will apply a 10% price adjustment to the products listed in the specified JSON file.

## Import Products API
To import products using the API, run the following `curl` command:
```bash
curl --location 'http://localhost:8001/products/import' \
--header 'Cookie: XSRF-TOKEN=eyJpdiI6Imc4eDgxQjJramFjaE9RY2s0N2pSNmc9PSIsInZhbHVlIjoid1RIbzgwUWJyWW1IUG05NkwwcDZuaFQ5WXBMR0hnZ2FmbGhRUWE3VUI2aVBNNVlCdnBPWnBZYzl2M2taTUJHWE9nR0FrTHVQVHNwem9HSDBQbXVkQUNEYnY3Y0lCZTQ4bisySU5IcnU0bXZsMklaenpGV2VqUVhkVUpSS213NVoiLCJtYWMiOiI2NmRkNjEzZTk5YTVlNWJkMGVkYmI1MjJmNjIyOWJiYjFjOGQ0MDkzZTEyMmNhZjU5Yzg2YzY2MzVkOWNiMzZhIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6Im1OWW5TRE1NQXBrMVI1b0drZk1TWUE9PSIsInZhbHVlIjoic3lTRkVyZzY3SVVWUDBHaGYrTE1XamJsZndDL0dNVEtSZ09vY0w5UGlYWjkyc0xyK1dvcVlQQTUvRnYwYlg5R2RhOHVPd3UvVElIT2JBKzBhNWRaT1BZRGFtRklJb24yS0x1Y1c4TmtzSk9vN0xNTFI1RDhQbEdsR0lYbGtLaTkiLCJtYWMiOiIzODA4YjJlMzg5YmFiZDgwOWYwYTEyYTkwOTY1MzJiZWUyMjZmYzliYWExYWE5OWY4ZDkwYzM4ZWRkYTYyZjIzIiwidGFnIjoiIn0%3D' \
--form 'products_file=@"/Users/hentryfryzen/Downloads/products.json"'
```
Get Products
```bash
curl --location 'http://localhost:8001/products' \
```
Get By ID Products:
```bash
curl --location 'http://localhost:8001/products/{id}' \
```
## Common Issues & Fixes
### 1. Permissions Issue
If you encounter permission errors, try setting correct ownership:
```bash
docker exec -it app chown -R www-data:www-data /var/www/html
```

### 2. Database Connection Issues
Make sure the database service is running:
```bash
docker-compose logs db
```

## Contributing
Feel free to submit issues or pull requests to improve this setup.

## License
This project is open-source and available under the MIT License.

