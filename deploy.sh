#!/bin/bash
################################################################################
# Ubuntu VPS Deployment Script for CakePHP Hospital Management System
################################################################################
#
# This script automates the initial deployment of the CakePHP application
# on an Ubuntu VPS. Run with sudo privileges.
#
# Usage: sudo bash deploy.sh
#
################################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration variables (MODIFY THESE)
APP_DOMAIN="yourhospital.com"
APP_DIR="/var/www/hospital"
DB_NAME="hospital_management"
DB_USER="cakephp_user"
DB_PASS=""  # Will prompt if empty
DEPLOY_USER="cakephp"
ADMIN_EMAIL="admin@yourhospital.com"
USE_NGINX=false  # Set to true for Nginx, false for Apache
PHP_VERSION="8.1"

################################################################################
# Functions
################################################################################

print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

prompt_confirmation() {
    read -p "$1 (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_error "Operation cancelled by user"
        exit 1
    fi
}

generate_password() {
    openssl rand -base64 32 | tr -d "=+/" | cut -c1-25
}

generate_salt() {
    php -r "echo bin2hex(random_bytes(32));"
}

################################################################################
# Pre-flight checks
################################################################################

print_message "Starting deployment process..."

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   print_error "This script must be run as root (use sudo)"
   exit 1
fi

# Check Ubuntu version
if ! grep -q "Ubuntu" /etc/os-release; then
    print_warning "This script is designed for Ubuntu. Proceed with caution."
    prompt_confirmation "Continue anyway?"
fi

print_message "Configuration:"
echo "  Domain: $APP_DOMAIN"
echo "  Application Directory: $APP_DIR"
echo "  Database Name: $DB_NAME"
echo "  Database User: $DB_USER"
echo "  Web Server: $([ "$USE_NGINX" = true ] && echo "Nginx" || echo "Apache")"
echo "  PHP Version: $PHP_VERSION"
echo ""

prompt_confirmation "Continue with these settings?"

################################################################################
# Generate passwords if not set
################################################################################

if [ -z "$DB_PASS" ]; then
    print_message "Generating database password..."
    DB_PASS=$(generate_password)
fi

print_message "Generating security salt..."
SECURITY_SALT=$(generate_salt)

################################################################################
# Update system
################################################################################

print_message "Updating system packages..."
apt update
apt upgrade -y

################################################################################
# Install software
################################################################################

print_message "Installing required software..."

# Install basic tools
apt install -y software-properties-common curl wget git unzip ufw fail2ban

# Add PHP repository
add-apt-repository -y ppa:ondrej/php
apt update

# Install PHP and extensions
print_message "Installing PHP $PHP_VERSION and extensions..."
apt install -y \
    php$PHP_VERSION \
    php$PHP_VERSION-cli \
    php$PHP_VERSION-fpm \
    php$PHP_VERSION-mysql \
    php$PHP_VERSION-xml \
    php$PHP_VERSION-mbstring \
    php$PHP_VERSION-intl \
    php$PHP_VERSION-curl \
    php$PHP_VERSION-zip \
    php$PHP_VERSION-gd \
    php$PHP_VERSION-bcmath \
    php$PHP_VERSION-opcache

# Install web server
if [ "$USE_NGINX" = true ]; then
    print_message "Installing Nginx..."
    apt install -y nginx
    systemctl enable nginx
else
    print_message "Installing Apache..."
    apt install -y apache2 libapache2-mod-php$PHP_VERSION
    a2enmod rewrite headers ssl
    systemctl enable apache2
fi

# Install MySQL/MariaDB
print_message "Installing MariaDB..."
apt install -y mariadb-server mariadb-client
systemctl enable mariadb
systemctl start mariadb

# Install Composer
if ! command -v composer &> /dev/null; then
    print_message "Installing Composer..."
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm /tmp/composer-setup.php
fi

################################################################################
# Configure firewall
################################################################################

print_message "Configuring firewall..."
ufw --force enable
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp

################################################################################
# Create deployment user
################################################################################

if ! id "$DEPLOY_USER" &>/dev/null; then
    print_message "Creating deployment user: $DEPLOY_USER"
    adduser --disabled-password --gecos "" $DEPLOY_USER
    usermod -aG www-data $DEPLOY_USER
fi

################################################################################
# Configure database
################################################################################

print_message "Configuring database..."

# Create database and user
mysql -u root <<MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT

print_message "Database configured successfully"

################################################################################
# Set up application directory
################################################################################

print_message "Setting up application directory..."

mkdir -p $APP_DIR
chown $DEPLOY_USER:www-data $APP_DIR

# If running in git repo, assume we're already in the right place
if [ -f "composer.json" ] && [ -f "config/app.php" ]; then
    print_message "Detected CakePHP application in current directory"
    if [ "$(pwd)" != "$APP_DIR" ]; then
        print_message "Copying application to $APP_DIR..."
        cp -r . $APP_DIR/
    fi
else
    print_warning "CakePHP application not found in current directory"
    print_message "Please manually clone/copy your application to $APP_DIR"
    exit 1
fi

cd $APP_DIR

################################################################################
# Install dependencies
################################################################################

print_message "Installing Composer dependencies..."
sudo -u $DEPLOY_USER composer install --no-dev --optimize-autoloader

################################################################################
# Configure application
################################################################################

print_message "Configuring application..."

# Create .env file
if [ ! -f "config/.env" ]; then
    cat > config/.env <<ENV_CONFIG
export APP_NAME="HospitalManagement"
export DEBUG="false"
export APP_ENCODING="UTF-8"
export APP_DEFAULT_LOCALE="en_US"
export APP_DEFAULT_TIMEZONE="UTC"
export SECURITY_SALT="$SECURITY_SALT"
export DATABASE_URL="mysql://$DB_USER:$DB_PASS@localhost/$DB_NAME?encoding=utf8mb4&timezone=UTC&cacheMetadata=true&quoteIdentifiers=false&persistent=false"
ENV_CONFIG
    chown $DEPLOY_USER:www-data config/.env
    chmod 640 config/.env
fi

# Create app_local.php
if [ ! -f "config/app_local.php" ]; then
    cat > config/app_local.php <<'PHP_CONFIG'
<?php
return [
    'debug' => false,
    'Security' => [
        'salt' => env('SECURITY_SALT'),
    ],
    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            'username' => env('DB_USER', 'DB_USER_PLACEHOLDER'),
            'password' => env('DB_PASS', 'DB_PASS_PLACEHOLDER'),
            'database' => env('DB_NAME', 'DB_NAME_PLACEHOLDER'),
            'url' => env('DATABASE_URL', null),
        ],
    ],
];
PHP_CONFIG

    # Replace placeholders
    sed -i "s/DB_USER_PLACEHOLDER/$DB_USER/g" config/app_local.php
    sed -i "s/DB_PASS_PLACEHOLDER/$DB_PASS/g" config/app_local.php
    sed -i "s/DB_NAME_PLACEHOLDER/$DB_NAME/g" config/app_local.php

    chown $DEPLOY_USER:www-data config/app_local.php
    chmod 640 config/app_local.php
fi

################################################################################
# Set permissions
################################################################################

print_message "Setting file permissions..."

chown -R $DEPLOY_USER:www-data $APP_DIR
find $APP_DIR -type d -exec chmod 755 {} \;
find $APP_DIR -type f -exec chmod 644 {} \;

# Writable directories
chmod -R 775 tmp logs
mkdir -p webroot/files
chmod -R 775 webroot/files
chgrp -R www-data tmp logs webroot/files

################################################################################
# Run migrations
################################################################################

print_message "Running database migrations..."
sudo -u $DEPLOY_USER bin/cake migrations migrate

print_message "Seeding database..."
sudo -u $DEPLOY_USER bin/cake migrations seed

################################################################################
# Clear cache
################################################################################

print_message "Clearing cache..."
sudo -u $DEPLOY_USER bin/cake cache clear_all

################################################################################
# Configure web server
################################################################################

if [ "$USE_NGINX" = true ]; then
    print_message "Configuring Nginx..."

    cat > /etc/nginx/sites-available/hospital <<NGINX_CONFIG
server {
    listen 80;
    listen [::]:80;
    server_name $APP_DOMAIN www.$APP_DOMAIN;
    root $APP_DIR/webroot;
    index index.php;

    access_log /var/log/nginx/hospital_access.log;
    error_log /var/log/nginx/hospital_error.log;

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php$PHP_VERSION-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
NGINX_CONFIG

    ln -sf /etc/nginx/sites-available/hospital /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
    nginx -t
    systemctl restart nginx

else
    print_message "Configuring Apache..."

    cat > /etc/apache2/sites-available/hospital.conf <<APACHE_CONFIG
<VirtualHost *:80>
    ServerName $APP_DOMAIN
    ServerAlias www.$APP_DOMAIN
    ServerAdmin $ADMIN_EMAIL
    DocumentRoot $APP_DIR/webroot

    <Directory $APP_DIR/webroot>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^ index.php [L]
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/hospital_error.log
    CustomLog \${APACHE_LOG_DIR}/hospital_access.log combined

    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
</VirtualHost>
APACHE_CONFIG

    a2ensite hospital.conf
    a2dissite 000-default.conf
    apache2ctl configtest
    systemctl restart apache2
fi

################################################################################
# Configure PHP
################################################################################

print_message "Configuring PHP..."

PHP_INI="/etc/php/$PHP_VERSION/apache2/php.ini"
[ "$USE_NGINX" = true ] && PHP_INI="/etc/php/$PHP_VERSION/fpm/php.ini"

# Backup original
cp $PHP_INI ${PHP_INI}.backup

# Update settings
sed -i 's/^expose_php = .*/expose_php = Off/' $PHP_INI
sed -i 's/^display_errors = .*/display_errors = Off/' $PHP_INI
sed -i 's/^;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' $PHP_INI
sed -i 's/^memory_limit = .*/memory_limit = 256M/' $PHP_INI
sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 20M/' $PHP_INI
sed -i 's/^post_max_size = .*/post_max_size = 20M/' $PHP_INI

# Enable OPcache
cat > /etc/php/$PHP_VERSION/mods-available/opcache.ini <<OPCACHE_CONFIG
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.save_comments=1
OPCACHE_CONFIG

# Restart PHP service
if [ "$USE_NGINX" = true ]; then
    systemctl restart php$PHP_VERSION-fpm
else
    systemctl restart apache2
fi

################################################################################
# Set up backups
################################################################################

print_message "Setting up backup scripts..."

mkdir -p /var/backups/hospital

# Database backup script
cat > /usr/local/bin/backup-hospital-db.sh <<BACKUP_DB
#!/bin/bash
BACKUP_DIR="/var/backups/hospital"
TIMESTAMP=\$(date +"%Y%m%d_%H%M%S")
mysqldump -u$DB_USER -p'$DB_PASS' $DB_NAME | gzip > \$BACKUP_DIR/db_backup_\$TIMESTAMP.sql.gz
find \$BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete
BACKUP_DB

chmod +x /usr/local/bin/backup-hospital-db.sh

# Files backup script
cat > /usr/local/bin/backup-hospital-files.sh <<BACKUP_FILES
#!/bin/bash
BACKUP_DIR="/var/backups/hospital"
TIMESTAMP=\$(date +"%Y%m%d_%H%M%S")
tar -czf \$BACKUP_DIR/files_backup_\$TIMESTAMP.tar.gz \
    $APP_DIR/webroot/files \
    $APP_DIR/config/app_local.php \
    $APP_DIR/config/.env
find \$BACKUP_DIR -name "files_backup_*.tar.gz" -mtime +7 -delete
BACKUP_FILES

chmod +x /usr/local/bin/backup-hospital-files.sh

# Add to crontab
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-hospital-db.sh >> /var/log/backup.log 2>&1") | crontab -
(crontab -l 2>/dev/null; echo "0 3 * * * /usr/local/bin/backup-hospital-files.sh >> /var/log/backup.log 2>&1") | crontab -

################################################################################
# Summary
################################################################################

print_message "=============================================="
print_message "Deployment completed successfully!"
print_message "=============================================="
echo ""
print_message "Application Details:"
echo "  Application URL: http://$APP_DOMAIN"
echo "  Application Directory: $APP_DIR"
echo ""
print_message "Database Details:"
echo "  Database Name: $DB_NAME"
echo "  Database User: $DB_USER"
echo "  Database Password: $DB_PASS"
echo ""
print_message "Security Salt: $SECURITY_SALT"
echo ""
print_warning "IMPORTANT: Save these credentials securely!"
echo ""
print_message "Next Steps:"
echo "  1. Configure your domain DNS to point to this server"
echo "  2. Install SSL certificate: sudo certbot --$([ "$USE_NGINX" = true ] && echo "nginx" || echo "apache") -d $APP_DOMAIN -d www.$APP_DOMAIN"
echo "  3. Access your application at http://$APP_DOMAIN"
echo "  4. Login to admin panel at http://$APP_DOMAIN/admin"
echo "  5. Review /var/log/apache2/hospital_error.log (or nginx) for any issues"
echo ""
print_message "Backup scripts installed and scheduled daily"
print_message "Manual backup: /usr/local/bin/backup-hospital-db.sh"
echo ""

# Write credentials to file
CREDS_FILE="/root/hospital-deployment-credentials.txt"
cat > $CREDS_FILE <<CREDS
Hospital Management System - Deployment Credentials
====================================================
Date: $(date)

Application Details:
- URL: http://$APP_DOMAIN
- Directory: $APP_DIR
- Deploy User: $DEPLOY_USER

Database:
- Name: $DB_NAME
- User: $DB_USER
- Password: $DB_PASS

Security:
- Security Salt: $SECURITY_SALT

Web Server: $([ "$USE_NGINX" = true ] && echo "Nginx" || echo "Apache")
PHP Version: $PHP_VERSION

IMPORTANT: Keep this file secure and delete after saving credentials elsewhere!
CREDS

chmod 600 $CREDS_FILE
print_message "Credentials saved to: $CREDS_FILE"

exit 0
