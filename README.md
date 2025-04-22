# Adaptive Auth

A web application built with PHP (Laravel) and Livewire, using MySQL as the database, Nginx as the web server, and Node.js for frontend assets.

---

## 🚀 Requirements

Before you begin, make sure you have the following installed:

- PHP >= 8.1
- Composer
- MySQL
- Nginx
- Node.js & npm

---

## 🛠️ Installation

```bash
# Clone the repository
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name

# Install PHP dependencies
composer install

# Copy the environment file
cp .env.example .env

# Set up your database configuration inside .env
# Example:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Generate application key
php artisan key:generate

# Run database migrations (optional seed if needed)
php artisan migrate --seed

# Install Node.js dependencies
npm install

# Build frontend assets for local development
npm run dev
