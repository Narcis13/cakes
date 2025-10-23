# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a CakePHP 5.1 hospital management system with both public-facing website and admin panel functionality.

## Essential Commands

### Development
```bash
# Start development server
bin/cake server -p 8765

# Run database migrations
bin/cake migrations migrate

# Seed database with initial data
bin/cake migrations seed

# Clear cache
bin/cake cache clear_all
```

### Testing & Code Quality
```bash
# Run all tests
composer test

# Check code standards
composer cs-check

# Fix code standards automatically
composer cs-fix

# Run PHPStan static analysis (level 8)
composer stan

# Run all checks (tests + standards)
composer check
```

### Database Operations
```bash
# Create a new migration
bin/cake bake migration CreateTableName

# Run specific migration
bin/cake migrations migrate -t <migration_version>

# Rollback migrations
bin/cake migrations rollback

# Check migration status
bin/cake migrations status
```

## Architecture Overview

### MVC Structure
- **Controllers**: `/src/Controller/` - Split between public controllers and `/Admin/` namespace
- **Models**: `/src/Model/Table/` and `/src/Model/Entity/` - Database interaction layer
- **Views**: `/templates/` - Template files organized by controller
- **View Cells**: `/src/View/Cell/` - Reusable view components for modular UI

### Key Application Components

1. **Public Website** (`/`)
   - Home page with sections managed through cells (Hero, Services, Departments, etc.)
   - Contact form with email notifications
   - Dynamic pages system
   - News/blog functionality
   - Staff/doctors directory
   - Department listings
   - Appointment booking

2. **Admin Panel** (`/admin`)
   - Dashboard with statistics
   - Full CRUD for all entities
   - File management system
   - Navigation builder
   - Site settings management
   - User role management

3. **Database Schema**
   - Uses migrations in `/config/Migrations/`
   - Key tables: departments, staff, services, news, appointments, pages, files, settings, users
   - Supports dynamic page components
   - Role-based access control

### Configuration
- Main config: `/config/app.php` (committed)
- Local overrides: `/config/app_local.php` (not committed)
- Database config in app_local.php under 'Datasources'
- Routes defined in `/config/routes.php`

### Testing Approach
- PHPUnit for unit and integration tests
- Test files in `/tests/` directory
- Fixtures in `/tests/Fixture/`
- Run specific test: `vendor/bin/phpunit tests/TestCase/Controller/PagesControllerTest.php`

### Code Standards
- Follows CakePHP coding standards
- PHPStan level 8 for type safety
- Use `composer cs-fix` before committing
- Naming conventions:
  - Controllers: PascalCase with "Controller" suffix
  - Tables: PascalCase with "Table" suffix
  - Entities: PascalCase singular
  - Templates: snake_case matching action names

### Security Considerations
- Authentication handled by CakePHP's authentication plugin
- CSRF protection enabled by default
- Form validation in Table classes
- Sanitization in templates using `h()` helper
- File uploads restricted by type and size

### Development Workflow
1. Always run migrations after pulling changes
2. Clear cache when routes or configuration changes
3. Run tests before committing
4. Fix code standards issues before push
5. Use bake commands for generating boilerplate:
   ```bash
   bin/cake bake controller ControllerName
   bin/cake bake model TableName
   bin/cake bake template ControllerName action_name
   ```

### Frontend Assets
- CSS framework: Milligram v1.3
- Assets in `/webroot/` (css, js, img directories)
- Medical theme template reference in `/Medilab/`
- No build process - assets served directly

### Common Patterns
- Use Table classes for queries and business logic
- Entities for data representation
- View Cells for reusable UI components
- Form helper for generating forms
- Flash messages for user feedback
- Pagination helper for lists

## Deployment

### Deployment Resources
This repository includes comprehensive deployment documentation:

1. **UBUNTU_DEPLOYMENT_GUIDE.md** - Complete step-by-step deployment guide covering:
   - Server requirements and initial setup
   - Software installation (Apache/Nginx, PHP, MySQL)
   - Database configuration
   - Application deployment and configuration
   - SSL/HTTPS setup with Let's Encrypt
   - Security hardening
   - Performance optimization
   - Monitoring and logging
   - Backup strategies
   - Troubleshooting guide

2. **deploy.sh** - Automated deployment script that:
   - Installs all required software
   - Configures database and creates credentials
   - Sets up the application with proper permissions
   - Configures web server (Apache or Nginx)
   - Sets up automated backups
   - Hardens PHP and system security
   - Usage: `sudo bash deploy.sh` (edit configuration variables first)

3. **DEPLOYMENT_CHECKLIST.md** - Comprehensive checklist covering:
   - Pre-deployment preparation
   - Installation steps
   - Configuration verification
   - Security measures
   - Testing procedures
   - Post-deployment tasks
   - Maintenance schedule

### Quick Deployment
For Ubuntu VPS deployment:
```bash
# 1. Edit configuration variables in deploy.sh
nano deploy.sh

# 2. Run automated deployment
sudo bash deploy.sh

# 3. Install SSL certificate
sudo certbot --apache -d yourdomain.com  # or --nginx

# 4. Follow post-deployment checklist
```

### Manual Deployment
For detailed manual deployment or troubleshooting, refer to `UBUNTU_DEPLOYMENT_GUIDE.md`.