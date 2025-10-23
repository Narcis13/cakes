# Ubuntu VPS Deployment Guide
## CakePHP 5.1 Hospital Management System

This comprehensive guide will walk you through deploying your CakePHP hospital management application to an Ubuntu VPS with full production configuration.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Server Requirements](#server-requirements)
3. [Initial Server Setup](#initial-server-setup)
4. [Install Required Software](#install-required-software)
5. [Database Setup](#database-setup)
6. [Application Deployment](#application-deployment)
7. [Web Server Configuration](#web-server-configuration)
8. [SSL/HTTPS Setup](#sslhttps-setup)
9. [Security Hardening](#security-hardening)
10. [Performance Optimization](#performance-optimization)
11. [Monitoring & Logging](#monitoring--logging)
12. [Backup Strategy](#backup-strategy)
13. [Troubleshooting](#troubleshooting)

---

## Prerequisites

- Ubuntu VPS (20.04 LTS or 22.04 LTS recommended)
- Root or sudo access
- Domain name pointed to your VPS IP
- SSH access configured
- Minimum 2GB RAM, 2 CPU cores, 20GB storage

---

## Server Requirements

### Software Stack
- **OS**: Ubuntu 20.04 LTS or 22.04 LTS
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 8.1, 8.2, or 8.3
- **Database**: MySQL 8.0+ or MariaDB 10.6+
- **Composer**: 2.x
- **Git**: For deployment

### PHP Extensions Required
- ext-intl
- ext-mbstring
- ext-pdo_mysql
- ext-xml
- ext-curl
- ext-zip
- ext-gd (for image handling)
- ext-json
- ext-openssl

---

## Initial Server Setup

### 1. Update System Packages

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Set Timezone

```bash
sudo timedatectl set-timezone UTC
# Or your preferred timezone:
# sudo timedatectl set-timezone America/New_York
```

### 3. Create Deployment User

```bash
# Create a user for the application
sudo adduser cakephp
sudo usermod -aG www-data cakephp

# Add to sudoers if needed
sudo usermod -aG sudo cakephp
```

### 4. Configure Firewall

```bash
sudo apt install ufw -y
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status
```

---

## Install Required Software

### 1. Install Apache Web Server

```bash
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2

# Enable required Apache modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl
sudo systemctl restart apache2
```

**Alternative: Install Nginx** (if you prefer Nginx over Apache)

```bash
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx
```

### 2. Install MySQL/MariaDB

```bash
# Option A: MySQL
sudo apt install mysql-server -y

# Option B: MariaDB (recommended)
sudo apt install mariadb-server mariadb-client -y

# Start and enable
sudo systemctl enable mysql  # or mariadb
sudo systemctl start mysql

# Secure installation
sudo mysql_secure_installation
```

During `mysql_secure_installation`:
- Set root password: **YES**
- Remove anonymous users: **YES**
- Disallow root login remotely: **YES**
- Remove test database: **YES**
- Reload privilege tables: **YES**

### 3. Install PHP 8.1+ with Extensions

```bash
# Add PHP repository (for Ubuntu 20.04)
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.1 and required extensions
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml \
  php8.1-mbstring php8.1-intl php8.1-curl php8.1-zip php8.1-gd \
  php8.1-bcmath php8.1-opcache libapache2-mod-php8.1 -y

# Verify PHP installation
php -v
```

### 4. Install Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Verify installation
composer --version
```

### 5. Install Git

```bash
sudo apt install git -y
git --version
```

---

## Database Setup

### 1. Create Database and User

```bash
sudo mysql -u root -p
```

In MySQL console:

```sql
-- Create database
CREATE DATABASE hospital_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with strong password
CREATE USER 'cakephp_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD_HERE';

-- Grant privileges
GRANT ALL PRIVILEGES ON hospital_management.* TO 'cakephp_user'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Exit
EXIT;
```

### 2. Test Database Connection

```bash
mysql -u cakephp_user -p hospital_management
```

---

## Application Deployment

### 1. Clone/Upload Application

**Option A: Using Git (Recommended)**

```bash
# Switch to deployment user
su - cakephp

# Clone repository
cd /var/www
sudo mkdir -p hospital
sudo chown cakephp:www-data hospital
cd hospital
git clone YOUR_REPOSITORY_URL .

# Or if you're pushing from local:
# git init
# git remote add origin YOUR_REPOSITORY_URL
# git pull origin main
```

**Option B: Upload via SCP/SFTP**

From your local machine:
```bash
# Compress the application (exclude node_modules, vendor, etc.)
tar -czf cakephp-app.tar.gz --exclude='vendor' --exclude='node_modules' \
  --exclude='.git' --exclude='tmp/*' --exclude='logs/*' .

# Upload to server
scp cakephp-app.tar.gz cakephp@YOUR_SERVER_IP:/var/www/hospital/

# On server: Extract
cd /var/www/hospital
tar -xzf cakephp-app.tar.gz
rm cakephp-app.tar.gz
```

### 2. Install Dependencies

```bash
cd /var/www/hospital
composer install --no-dev --optimize-autoloader
```

### 3. Set Proper Permissions

```bash
cd /var/www/hospital

# Set ownership
sudo chown -R cakephp:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;

# Set writable directories
sudo chmod -R 775 tmp
sudo chmod -R 775 logs
sudo chmod -R 775 webroot/files

# Ensure web server can write
sudo chgrp -R www-data tmp logs webroot/files
```

### 4. Configure Application

```bash
cd /var/www/hospital/config

# Copy example files
cp .env.example .env
cp app_local.example.php app_local.php

# Generate security salt
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
# Copy the output
```

**Edit .env file:**

```bash
nano .env
```

Update with your values:
```bash
export APP_NAME="HospitalManagement"
export DEBUG="false"  # IMPORTANT: Set to false in production
export APP_ENCODING="UTF-8"
export APP_DEFAULT_LOCALE="en_US"
export APP_DEFAULT_TIMEZONE="UTC"
export SECURITY_SALT="YOUR_GENERATED_SALT_HERE"

# Database configuration
export DATABASE_URL="mysql://cakephp_user:YOUR_STRONG_PASSWORD_HERE@localhost/hospital_management?encoding=utf8mb4&timezone=UTC&cacheMetadata=true&quoteIdentifiers=false&persistent=false"

# Email configuration (if using SMTP)
export EMAIL_TRANSPORT_DEFAULT_URL="smtp://username:password@smtp.example.com:587?tls=true"
```

**Edit app_local.php:**

```bash
nano app_local.php
```

Update database credentials:
```php
<?php
return [
    'debug' => false, // IMPORTANT: Set to false in production

    'Security' => [
        'salt' => env('SECURITY_SALT', 'YOUR_GENERATED_SALT_HERE'),
    ],

    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            'username' => 'cakephp_user',
            'password' => 'YOUR_STRONG_PASSWORD_HERE',
            'database' => 'hospital_management',
            'url' => env('DATABASE_URL', null),
        ],
    ],

    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'your-email@example.com',
            'password' => 'your-email-password',
            'tls' => true,
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => ['noreply@yourhospital.com' => 'Hospital Management System'],
        ],
    ],
];
```

### 5. Run Database Migrations

```bash
cd /var/www/hospital

# Run migrations
bin/cake migrations migrate

# Seed database with initial data
bin/cake migrations seed
```

### 6. Clear Cache

```bash
bin/cake cache clear_all
```

---

## Web Server Configuration

### Apache Configuration

#### 1. Create Virtual Host

```bash
sudo nano /etc/apache2/sites-available/hospital.conf
```

Add the following configuration:

```apache
<VirtualHost *:80>
    ServerName yourhospital.com
    ServerAlias www.yourhospital.com
    ServerAdmin admin@yourhospital.com

    DocumentRoot /var/www/hospital/webroot

    <Directory /var/www/hospital/webroot>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted

        # Enable rewrite
        RewriteEngine On
        RewriteBase /

        # If the file exists, serve it
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^ index.php [L]
    </Directory>

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/hospital_error.log
    CustomLog ${APACHE_LOG_DIR}/hospital_access.log combined

    # Security headers
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</VirtualHost>
```

#### 2. Enable Site and Restart Apache

```bash
# Enable the site
sudo a2ensite hospital.conf

# Disable default site
sudo a2dissite 000-default.conf

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

### Nginx Configuration (Alternative)

#### 1. Create Server Block

```bash
sudo nano /etc/nginx/sites-available/hospital
```

Add configuration:

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name yourhospital.com www.yourhospital.com;
    root /var/www/hospital/webroot;
    index index.php;

    access_log /var/log/nginx/hospital_access.log;
    error_log /var/log/nginx/hospital_error.log;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to .htaccess files
    location ~ /\.ht {
        deny all;
    }

    # Security headers
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
}
```

#### 2. Enable Site and Restart Nginx

```bash
# Create symlink
sudo ln -s /etc/nginx/sites-available/hospital /etc/nginx/sites-enabled/

# Remove default
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

---

## SSL/HTTPS Setup

### Using Let's Encrypt (Free SSL)

#### 1. Install Certbot

```bash
# For Apache
sudo apt install certbot python3-certbot-apache -y

# For Nginx
sudo apt install certbot python3-certbot-nginx -y
```

#### 2. Obtain SSL Certificate

```bash
# For Apache
sudo certbot --apache -d yourhospital.com -d www.yourhospital.com

# For Nginx
sudo certbot --nginx -d yourhospital.com -d www.yourhospital.com
```

Follow the prompts:
- Enter email address
- Agree to terms
- Choose whether to redirect HTTP to HTTPS (recommended: YES)

#### 3. Auto-renewal Setup

```bash
# Test auto-renewal
sudo certbot renew --dry-run

# Certbot automatically creates a cron job, verify:
sudo systemctl status certbot.timer
```

#### 4. Update CakePHP Configuration

Edit `config/app_local.php` to force HTTPS:

```php
'App' => [
    'fullBaseUrl' => 'https://yourhospital.com',
],
```

---

## Security Hardening

### 1. PHP Security Configuration

```bash
sudo nano /etc/php/8.1/apache2/php.ini
# or for Nginx:
sudo nano /etc/php/8.1/fpm/php.ini
```

Update these settings:

```ini
expose_php = Off
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php/error.log
max_execution_time = 30
max_input_time = 60
memory_limit = 256M
post_max_size = 20M
upload_max_filesize = 20M
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

Create PHP log directory:
```bash
sudo mkdir -p /var/log/php
sudo chown www-data:www-data /var/log/php
```

### 2. Disable Directory Listing

Already configured in Apache/Nginx configs above with:
- Apache: `Options -Indexes`
- Nginx: default behavior

### 3. Protect Sensitive Files

```bash
cd /var/www/hospital

# Protect configuration files
sudo chmod 640 config/app_local.php
sudo chmod 640 config/.env

# Make sure only owner and group can read
sudo chown cakephp:www-data config/app_local.php
sudo chown cakephp:www-data config/.env
```

### 4. Install Fail2Ban (Brute Force Protection)

```bash
sudo apt install fail2ban -y

# Create local configuration
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo nano /etc/fail2ban/jail.local
```

Enable SSH and Apache/Nginx protection:

```ini
[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600

[apache-auth]
enabled = true
port = http,https
logpath = /var/log/apache2/hospital_error.log
maxretry = 5
bantime = 3600
```

Restart Fail2Ban:
```bash
sudo systemctl restart fail2ban
sudo systemctl enable fail2ban
sudo fail2ban-client status
```

### 5. Configure CSRF Protection

CakePHP has CSRF protection enabled by default. Verify in `src/Application.php`:

```php
// Should be present in middleware stack
->add(new CsrfProtectionMiddleware([
    'httponly' => true,
]))
```

---

## Performance Optimization

### 1. Enable OPcache

```bash
sudo nano /etc/php/8.1/apache2/conf.d/10-opcache.ini
```

Configure OPcache:

```ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
```

### 2. Configure CakePHP Caching

Edit `config/app.php` or `app_local.php`:

```php
'Cache' => [
    'default' => [
        'className' => 'File',
        'path' => CACHE,
        'duration' => '+1 hours',
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'myapp_cake_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+1 years',
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'myapp_cake_model_',
        'path' => CACHE . 'models/',
        'serialize' => true,
        'duration' => '+1 years',
    ],
],
```

### 3. Database Query Caching

Enable in `config/app_local.php`:

```php
'Datasources' => [
    'default' => [
        // ... other settings
        'cacheMetadata' => true,
        'log' => false, // Disable query logging in production
    ],
],
```

### 4. Restart Services

```bash
sudo systemctl restart apache2  # or nginx
sudo systemctl restart php8.1-fpm  # if using Nginx
```

---

## Monitoring & Logging

### 1. Application Logs

CakePHP logs are in:
```
/var/www/hospital/logs/
```

View error logs:
```bash
tail -f /var/www/hospital/logs/error.log
tail -f /var/www/hospital/logs/debug.log
```

### 2. Web Server Logs

Apache:
```bash
tail -f /var/log/apache2/hospital_error.log
tail -f /var/log/apache2/hospital_access.log
```

Nginx:
```bash
tail -f /var/log/nginx/hospital_error.log
tail -f /var/log/nginx/hospital_access.log
```

### 3. Log Rotation

Create log rotation config:
```bash
sudo nano /etc/logrotate.d/cakephp
```

Add:
```
/var/www/hospital/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0644 cakephp www-data
}
```

### 4. Monitor Disk Space

```bash
# Check disk usage
df -h

# Check directory sizes
du -sh /var/www/hospital/*
```

### 5. Install Monitoring Tools (Optional)

```bash
# Install htop for process monitoring
sudo apt install htop -y

# Install netdata for real-time monitoring
bash <(curl -Ss https://my-netdata.io/kickstart.sh)
```

---

## Backup Strategy

### 1. Database Backups

Create backup script:
```bash
sudo nano /usr/local/bin/backup-hospital-db.sh
```

Add script:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/hospital"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
DB_NAME="hospital_management"
DB_USER="cakephp_user"
DB_PASS="YOUR_STRONG_PASSWORD_HERE"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$TIMESTAMP.sql.gz

# Keep only last 7 days of backups
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete

echo "Database backup completed: $BACKUP_DIR/db_backup_$TIMESTAMP.sql.gz"
```

Make executable:
```bash
sudo chmod +x /usr/local/bin/backup-hospital-db.sh
```

### 2. File Backups

Create file backup script:
```bash
sudo nano /usr/local/bin/backup-hospital-files.sh
```

Add:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/hospital"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
APP_DIR="/var/www/hospital"

mkdir -p $BACKUP_DIR

# Backup uploaded files
tar -czf $BACKUP_DIR/files_backup_$TIMESTAMP.tar.gz \
    $APP_DIR/webroot/files \
    $APP_DIR/config/app_local.php \
    $APP_DIR/config/.env

# Keep only last 7 days
find $BACKUP_DIR -name "files_backup_*.tar.gz" -mtime +7 -delete

echo "Files backup completed: $BACKUP_DIR/files_backup_$TIMESTAMP.tar.gz"
```

Make executable:
```bash
sudo chmod +x /usr/local/bin/backup-hospital-files.sh
```

### 3. Automate Backups with Cron

```bash
sudo crontab -e
```

Add cron jobs:
```cron
# Database backup daily at 2 AM
0 2 * * * /usr/local/bin/backup-hospital-db.sh >> /var/log/backup.log 2>&1

# File backup daily at 3 AM
0 3 * * * /usr/local/bin/backup-hospital-files.sh >> /var/log/backup.log 2>&1
```

### 4. Remote Backup (Optional)

For additional security, sync backups to remote server:

```bash
# Using rsync to remote server
rsync -avz /var/backups/hospital/ user@remote-server:/backups/hospital/

# Or use cloud storage (AWS S3, Google Cloud, etc.)
# Install AWS CLI and configure
apt install awscli -y
aws configure
aws s3 sync /var/backups/hospital/ s3://your-bucket/hospital-backups/
```

---

## Troubleshooting

### Common Issues and Solutions

#### 1. 500 Internal Server Error

**Check Apache error logs:**
```bash
tail -f /var/log/apache2/hospital_error.log
```

**Common causes:**
- Incorrect file permissions
- Missing .htaccess file
- PHP errors
- Database connection issues

**Solutions:**
```bash
# Fix permissions
cd /var/www/hospital
sudo chown -R cakephp:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 tmp logs webroot/files

# Verify .htaccess exists
ls -la webroot/.htaccess
ls -la .htaccess

# Check PHP error log
tail -f /var/log/php/error.log
```

#### 2. Database Connection Errors

**Test database connection:**
```bash
mysql -u cakephp_user -p hospital_management
```

**Check credentials in:**
- `/var/www/hospital/config/app_local.php`
- `/var/www/hospital/config/.env`

**Verify database exists:**
```sql
SHOW DATABASES;
```

#### 3. mod_rewrite Not Working

**Enable mod_rewrite:**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Check .htaccess files exist:**
```bash
ls -la /var/www/hospital/.htaccess
ls -la /var/www/hospital/webroot/.htaccess
```

#### 4. File Upload Issues

**Check upload directory permissions:**
```bash
sudo chmod -R 775 /var/www/hospital/webroot/files
sudo chown -R cakephp:www-data /var/www/hospital/webroot/files
```

**Check PHP upload settings:**
```bash
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

#### 5. Cache Issues

**Clear all caches:**
```bash
cd /var/www/hospital
bin/cake cache clear_all
rm -rf tmp/cache/*
```

**Clear OPcache:**
```bash
sudo systemctl restart php8.1-fpm  # for Nginx
sudo systemctl restart apache2     # for Apache
```

#### 6. Permission Denied Errors

**Reset all permissions:**
```bash
cd /var/www/hospital
sudo chown -R cakephp:www-data .
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;
sudo chmod -R 775 tmp logs webroot/files
```

#### 7. Email Not Sending

**Check email configuration in app_local.php**

**Test SMTP connection:**
```bash
telnet smtp.example.com 587
```

**Check logs:**
```bash
tail -f /var/www/hospital/logs/error.log
```

**For Resend integration, verify API key is set correctly**

---

## Deployment Checklist

Use this checklist before going live:

- [ ] Ubuntu server updated and secured
- [ ] Firewall configured (UFW)
- [ ] Web server installed and configured (Apache/Nginx)
- [ ] PHP 8.1+ installed with all extensions
- [ ] MySQL/MariaDB installed and secured
- [ ] Database created with proper user permissions
- [ ] Application code deployed
- [ ] Composer dependencies installed (production mode)
- [ ] Configuration files created (app_local.php, .env)
- [ ] Security salt generated and set
- [ ] Database credentials configured
- [ ] Migrations run successfully
- [ ] Database seeded with initial data
- [ ] File permissions set correctly
- [ ] Debug mode disabled (debug = false)
- [ ] Virtual host/server block configured
- [ ] SSL certificate installed (HTTPS)
- [ ] Security headers configured
- [ ] PHP hardened (php.ini)
- [ ] Fail2Ban installed and configured
- [ ] OPcache enabled and configured
- [ ] CakePHP caching configured
- [ ] Log rotation configured
- [ ] Backup scripts created
- [ ] Backup cron jobs scheduled
- [ ] Monitoring tools installed
- [ ] Application tested thoroughly
- [ ] Admin panel accessible
- [ ] Email functionality tested
- [ ] File uploads tested
- [ ] Domain DNS configured
- [ ] Appointment booking tested
- [ ] All CRUD operations verified

---

## Post-Deployment

### 1. Create Admin User

If not already seeded:
```bash
cd /var/www/hospital
bin/cake console
```

Or create via database:
```sql
INSERT INTO users (email, password, role, created, modified)
VALUES ('admin@yourhospital.com',
        '$2y$10$HASHED_PASSWORD',
        'admin',
        NOW(),
        NOW());
```

Generate password hash:
```bash
php -r "echo password_hash('your-password', PASSWORD_DEFAULT) . PHP_EOL;"
```

### 2. Test All Functionality

- Public website pages
- Admin login
- CRUD operations for all entities
- File uploads
- Contact form
- Appointment booking
- Email notifications
- Department listings
- Staff directory
- News/blog posts

### 3. Set Up Monitoring

Monitor:
- Server resources (CPU, RAM, Disk)
- Application errors
- Database performance
- SSL certificate expiration
- Backup completion

### 4. Documentation

Document:
- Server IP and credentials
- Database credentials
- Domain registrar details
- SSL certificate details
- Email SMTP details
- Admin panel URL and credentials
- Backup locations

---

## Maintenance Tasks

### Daily
- Monitor error logs
- Check server resources

### Weekly
- Verify backups completed
- Check disk space usage
- Review application logs

### Monthly
- Update system packages
- Update Composer dependencies (test first!)
- Review security logs
- Test backup restoration
- Check SSL certificate expiration

### As Needed
- Apply security patches
- Update CakePHP version
- Optimize database
- Clear old logs

---

## Update Application

### Pull Latest Changes

```bash
cd /var/www/hospital
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Run new migrations
bin/cake migrations migrate

# Clear cache
bin/cake cache clear_all

# Restart services
sudo systemctl restart apache2  # or nginx
```

---

## Rollback Procedure

If deployment fails:

```bash
# Restore database backup
gunzip < /var/backups/hospital/db_backup_TIMESTAMP.sql.gz | mysql -u cakephp_user -p hospital_management

# Restore files
tar -xzf /var/backups/hospital/files_backup_TIMESTAMP.tar.gz -C /

# Rollback git
git reset --hard PREVIOUS_COMMIT_HASH

# Reinstall dependencies
composer install --no-dev --optimize-autoloader

# Clear cache
bin/cake cache clear_all
```

---

## Support and Resources

- CakePHP Documentation: https://book.cakephp.org/5/en/index.html
- CakePHP Community: https://discourse.cakephp.org/
- Ubuntu Documentation: https://help.ubuntu.com/
- Let's Encrypt: https://letsencrypt.org/

---

## Security Contacts

Maintain a list of security contacts:
- Server administrator
- Application developer
- Database administrator
- Hosting provider support
- Security team

---

## Conclusion

You now have a fully deployed and secured CakePHP hospital management system on Ubuntu VPS. Regular maintenance, monitoring, and backups are essential for keeping your application running smoothly and securely.

**Remember:**
- Always test changes in a staging environment first
- Keep regular backups
- Monitor logs for errors and security issues
- Keep all software updated
- Use strong passwords
- Enable two-factor authentication where possible

Good luck with your deployment!
