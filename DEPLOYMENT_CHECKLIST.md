# Deployment Checklist

Use this checklist to ensure all steps are completed for a successful production deployment.

## Pre-Deployment

### Server Preparation
- [ ] Ubuntu VPS provisioned (minimum 2GB RAM, 2 CPU cores, 20GB storage)
- [ ] Root or sudo access confirmed
- [ ] SSH access configured
- [ ] Domain name registered
- [ ] Domain DNS A record pointed to server IP
- [ ] Server SSH keys configured
- [ ] Backup server/service ready (if applicable)

### Local Preparation
- [ ] All code tested locally
- [ ] All migrations tested
- [ ] Database seeds verified
- [ ] Code standards checked (`composer cs-check`)
- [ ] Static analysis passed (`composer stan`)
- [ ] All tests passed (`composer test`)
- [ ] Git repository clean and pushed
- [ ] Production branch created/tagged
- [ ] Sensitive data removed from code

## Installation

### System Setup
- [ ] System packages updated (`apt update && apt upgrade`)
- [ ] Timezone configured
- [ ] Firewall configured (UFW)
- [ ] Deployment user created
- [ ] Fail2Ban installed

### Software Installation
- [ ] Apache/Nginx installed
- [ ] PHP 8.1+ installed
- [ ] All required PHP extensions installed
- [ ] MySQL/MariaDB installed
- [ ] MySQL secured (`mysql_secure_installation`)
- [ ] Composer installed globally
- [ ] Git installed
- [ ] Certbot installed (for SSL)

## Database Setup

- [ ] Database created
- [ ] Database user created with strong password
- [ ] Privileges granted correctly
- [ ] Database connection tested
- [ ] Character set verified (utf8mb4)
- [ ] Collation verified (utf8mb4_unicode_ci)

## Application Deployment

### Code Deployment
- [ ] Application cloned/uploaded to `/var/www/hospital` (or your path)
- [ ] Composer dependencies installed (`composer install --no-dev --optimize-autoloader`)
- [ ] File ownership set correctly (`chown -R user:www-data`)
- [ ] Directory permissions set (755 for directories, 644 for files)
- [ ] Writable directories permissions set (775 for tmp, logs, webroot/files)

### Configuration
- [ ] `.env` file created from `.env.example`
- [ ] `app_local.php` created from `app_local.example.php`
- [ ] Security salt generated and configured
- [ ] Database credentials configured
- [ ] Debug mode set to FALSE
- [ ] Email/SMTP configured (if applicable)
- [ ] Resend API key configured (if using Resend)
- [ ] Full base URL configured
- [ ] Environment variables verified

### Database Migration
- [ ] Migrations run successfully (`bin/cake migrations migrate`)
- [ ] Database seeded (`bin/cake migrations seed`)
- [ ] All tables created and verified
- [ ] Initial admin user created
- [ ] Sample data verified

### Cache & Optimization
- [ ] Application cache cleared (`bin/cake cache clear_all`)
- [ ] OPcache enabled and configured
- [ ] CakePHP cache configuration verified
- [ ] Database query caching enabled

## Web Server Configuration

### Apache Configuration
- [ ] Virtual host file created
- [ ] DocumentRoot points to `/webroot`
- [ ] mod_rewrite enabled
- [ ] mod_headers enabled
- [ ] mod_ssl enabled
- [ ] `.htaccess` files present in root and webroot
- [ ] AllowOverride All configured
- [ ] Site enabled (`a2ensite`)
- [ ] Default site disabled
- [ ] Configuration tested (`apache2ctl configtest`)
- [ ] Apache restarted

### Nginx Configuration (Alternative)
- [ ] Server block created
- [ ] Root points to `/webroot`
- [ ] PHP-FPM configured
- [ ] try_files directive configured
- [ ] Site enabled (symlink created)
- [ ] Default site removed
- [ ] Configuration tested (`nginx -t`)
- [ ] Nginx restarted

## Security

### SSL/HTTPS
- [ ] Certbot installed
- [ ] SSL certificate obtained
- [ ] Certificate auto-renewal configured
- [ ] HTTP to HTTPS redirect enabled
- [ ] HTTPS tested and working
- [ ] Mixed content warnings resolved

### Application Security
- [ ] Debug mode disabled (`debug => false`)
- [ ] Security salt is unique and strong
- [ ] Database passwords are strong
- [ ] Config files have restricted permissions (640)
- [ ] Sensitive files not web-accessible
- [ ] CSRF protection enabled
- [ ] Security headers configured:
  - [ ] X-Content-Type-Options: nosniff
  - [ ] X-Frame-Options: SAMEORIGIN
  - [ ] X-XSS-Protection: 1; mode=block
  - [ ] Referrer-Policy configured

### PHP Security
- [ ] `expose_php` disabled
- [ ] `display_errors` disabled
- [ ] `display_startup_errors` disabled
- [ ] `log_errors` enabled
- [ ] Error log file configured
- [ ] File upload limits configured
- [ ] `disable_functions` configured
- [ ] Session security configured

### Server Security
- [ ] Root login disabled
- [ ] SSH key authentication enabled
- [ ] Password authentication disabled
- [ ] Fail2Ban configured and running
- [ ] Firewall rules configured
- [ ] Only necessary ports open (22, 80, 443)
- [ ] Automatic security updates configured (optional)

## Monitoring & Logging

### Logging
- [ ] Application error log configured
- [ ] Web server error log verified
- [ ] PHP error log configured
- [ ] Log rotation configured
- [ ] Log file permissions correct

### Monitoring
- [ ] Disk space monitoring setup
- [ ] Server resources monitored (optional: htop, netdata)
- [ ] Application health check configured
- [ ] Uptime monitoring configured (optional)
- [ ] SSL certificate expiration monitoring

## Backups

### Backup Configuration
- [ ] Backup directory created (`/var/backups/hospital`)
- [ ] Database backup script created
- [ ] Files backup script created
- [ ] Backup scripts executable
- [ ] Cron jobs configured for automated backups
- [ ] Backup retention policy configured (7 days)
- [ ] Test backup created
- [ ] Test restore performed
- [ ] Remote backup configured (optional)

## Testing

### Functionality Testing
- [ ] Homepage loads correctly
- [ ] Static pages accessible
- [ ] Admin panel accessible (`/admin`)
- [ ] Admin login working
- [ ] User authentication tested
- [ ] File uploads working
- [ ] Image uploads tested
- [ ] Contact form working
- [ ] Email sending tested
- [ ] Appointment booking tested
- [ ] CRUD operations tested for:
  - [ ] Departments
  - [ ] Staff/Doctors
  - [ ] Services
  - [ ] News/Blog posts
  - [ ] Pages
  - [ ] Appointments
  - [ ] Settings
  - [ ] Users
- [ ] Navigation menus working
- [ ] All View Cells rendering correctly
- [ ] Search functionality working (if applicable)
- [ ] Responsive design verified

### Performance Testing
- [ ] Page load times acceptable
- [ ] Database queries optimized
- [ ] Images optimized
- [ ] Cache working correctly
- [ ] OPcache verified

### Security Testing
- [ ] HTTPS working on all pages
- [ ] SSL certificate valid
- [ ] No mixed content warnings
- [ ] CSRF protection working
- [ ] SQL injection tested (basic)
- [ ] XSS protection verified
- [ ] File upload restrictions working
- [ ] Admin area requires authentication
- [ ] Password reset working
- [ ] Session timeout configured

## Post-Deployment

### Documentation
- [ ] Server credentials documented (securely)
- [ ] Database credentials documented
- [ ] Domain registrar details documented
- [ ] SSL certificate details documented
- [ ] Email/SMTP details documented
- [ ] Admin panel URL documented
- [ ] Admin credentials documented (securely)
- [ ] Backup locations documented
- [ ] Deployment date documented
- [ ] Deployment procedure documented

### User Setup
- [ ] Admin user created/verified
- [ ] Initial content added (if needed)
- [ ] Site settings configured
- [ ] Navigation menus configured
- [ ] Contact information updated
- [ ] Logo/branding uploaded
- [ ] Initial departments added
- [ ] Initial staff added
- [ ] Initial services added

### Handover
- [ ] Client training completed (if applicable)
- [ ] Documentation provided to client
- [ ] Access credentials shared securely
- [ ] Support contact information provided
- [ ] Maintenance schedule communicated

## Maintenance Schedule

### Daily Tasks
- [ ] Monitor error logs
- [ ] Check server resources
- [ ] Verify backups completed

### Weekly Tasks
- [ ] Review application logs
- [ ] Check disk space
- [ ] Review security logs
- [ ] Test site functionality

### Monthly Tasks
- [ ] Update system packages
- [ ] Review and update dependencies (test first!)
- [ ] Test backup restoration
- [ ] Check SSL certificate expiration (90 days for Let's Encrypt)
- [ ] Optimize database (if needed)
- [ ] Clear old logs
- [ ] Review performance metrics

### As Needed
- [ ] Apply security patches immediately
- [ ] Update CakePHP version (test in staging first)
- [ ] Update PHP version (test compatibility)
- [ ] Scale resources if needed

## Emergency Contacts

Document these for quick reference:

- [ ] Server administrator: _______________
- [ ] Application developer: _______________
- [ ] Database administrator: _______________
- [ ] Hosting provider support: _______________
- [ ] Domain registrar support: _______________
- [ ] SSL certificate provider: _______________

## Rollback Plan

- [ ] Previous stable version tagged in git
- [ ] Rollback procedure documented
- [ ] Database backup before deployment
- [ ] Files backup before deployment
- [ ] Rollback tested (if possible)

## Sign-off

- [ ] All checklist items completed
- [ ] Testing completed successfully
- [ ] Documentation completed
- [ ] Client/stakeholder approval received
- [ ] Deployment marked as successful

---

**Deployment Date**: _______________

**Deployed By**: _______________

**Approved By**: _______________

**Notes**:
```
[Space for any additional notes or observations]
```

---

## Quick Reference Commands

### Restart Services
```bash
sudo systemctl restart apache2    # Apache
sudo systemctl restart nginx      # Nginx
sudo systemctl restart php8.1-fpm # PHP-FPM
sudo systemctl restart mysql      # MySQL
```

### Clear Cache
```bash
cd /var/www/hospital
bin/cake cache clear_all
```

### Run Migrations
```bash
cd /var/www/hospital
bin/cake migrations migrate
```

### View Logs
```bash
tail -f /var/www/hospital/logs/error.log
tail -f /var/log/apache2/hospital_error.log
tail -f /var/log/nginx/hospital_error.log
```

### Manual Backup
```bash
/usr/local/bin/backup-hospital-db.sh
/usr/local/bin/backup-hospital-files.sh
```

### Check Disk Space
```bash
df -h
du -sh /var/www/hospital/*
```

### SSL Certificate Renewal
```bash
sudo certbot renew
```
