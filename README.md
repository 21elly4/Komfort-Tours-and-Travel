# Komfort Tours & Travel

A professional travel booking platform built with PHP 8.1+ following clean architecture principles. This production-ready system provides comprehensive travel management services including corporate tours, eco tours, events, retreats, road trips, and transfers.

## Features

### Core Functionality
- **User Authentication**: Role-based access control for travelers and admins
- **Multi-Service Booking**: Support for 6 different travel service types
- **Payment Integration**: M-Pesa payment gateway ready
- **Vehicle Management**: Complete fleet management system
- **Admin Dashboard**: Comprehensive admin panel
- **Destination Management**: Add, update, and manage travel destinations
- **Booking Management**: Full booking lifecycle management
- **User Profiles**: Personalized user dashboards
- **Reporting & Analytics**: Track revenue, bookings, and performance

### Security Features
- CSRF protection on all forms
- Input validation and sanitization
- Secure password hashing (bcrypt)
- SQL injection prevention via PDO prepared statements
- Session management with timeout
- Environment-based configuration
- Role-based access control

## Requirements

- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher
- **Composer**: Latest version
- **Web Server**: Apache with mod_rewrite enabled (or Nginx)
- **Extensions**: PDO, PDO_MySQL, OpenSSL, Mbstring

## Installation

### 1. Clone the Repository
```bash
cd c:/Users/Ellymacc/Documents/Business_work/Private/Tours&Travels/Komfort/Komfort
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
```bash
cp .env.example .env
```

Edit the `.env` file with your configuration:
```env
APP_NAME=Komfort
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=localhost
DB_PORT=3306
DB_NAME=tours_travel_db
DB_USER=root
DB_PASS=

APP_KEY=generate-32-char-random-string
```

### 4. Generate Application Key
Generate a secure 32-character random string for `APP_KEY` in your `.env` file:
```bash
php -r "echo bin2hex(random_bytes(32));"
```

### 5. Import Database Schema
```bash
mysql -u your_username -p tours_travel_db < database/schema.sql
```

### 6. Configure Web Server

#### Apache
Point your web server to the `public/` directory and ensure mod_rewrite is enabled.

#### Nginx
Add the following configuration:
```nginx
server {
    listen 80;
    server_name localhost;
    root /path/to/Komfort/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 7. Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 storage/logs/
chmod -R 755 storage/cache/
chmod -R 755 storage/uploads/
```

## Default Credentials

**Admin User:**
- Email: admin@komfort.com
- Password: admin123

⚠️ **Important**: Change the default admin password immediately after first login!

## Project Structure

```
Komfort/
├── app/
│   ├── Controllers/     # Application controllers
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── HomeController.php
│   ├── Middleware/     # Authentication, CSRF, etc.
│   │   ├── Auth.php
│   │   └── Csrf.php
│   ├── Models/         # Data models
│   │   ├── BaseModel.php
│   │   ├── Booking.php
│   │   ├── Destination.php
│   │   ├── Payment.php
│   │   ├── ServiceType.php
│   │   ├── User.php
│   │   └── Vehicle.php
│   └── Views/          # View templates
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       ├── about.php
│       ├── contact.php
│       ├── home.php
│       └── services.php
├── config/             # Configuration files
│   ├── App.php
│   ├── Database.php
│   └── Logger.php
├── database/           # Database schema
│   └── schema.sql
├── public/             # Web root directory
│   ├── index.php       # Application entry point
│   ├── .htaccess       # Apache configuration
│   └── assets/         # CSS, JS, images
├── storage/            # Logs, cache, uploads
│   ├── logs/
│   ├── cache/
│   └── uploads/
├── tests/              # PHPUnit tests
├── vendor/             # Composer dependencies
├── .env                # Environment configuration
├── .env.example        # Environment template
├── .gitignore          # Git ignore rules
├── composer.json       # PHP dependencies
└── README.md           # This file
```

## Available Routes

### Public Routes
- `GET /` - Home page
- `GET /about` - About us page
- `GET /contact` - Contact page
- `GET /services` - Services page
- `GET /login` - Login page
- `GET /register` - Registration page

### Authentication Routes
- `POST /login` - Handle login
- `POST /register` - Handle registration
- `POST /logout` - Handle logout

### Dashboard Routes (Requires Authentication)
- `GET /dashboard` - User dashboard
- `GET /dashboard/profile` - User profile
- `POST /dashboard/profile` - Update profile

### Admin Routes (Requires Admin Role)
- `GET /admin/dashboard` - Admin dashboard
- (Additional admin routes to be implemented)

## Database Schema

The application uses the following main tables:

- **users** - User accounts (travelers and admins)
- **destinations** - Travel destinations
- **vehicles** - Vehicle fleet management
- **service_types** - Available service types
- **bookings** - Travel bookings
- **payments** - Payment records
- **itineraries** - Booking itineraries
- **vehicle_assignments** - Vehicle to booking assignments
- **vehicle_performance** - Vehicle performance tracking
- **password_reset_tokens** - Password reset functionality
- **sessions** - Enhanced session management

## Development

### Running Tests
```bash
composer test
```

### Code Style Check
```bash
composer cs-check
```

### Code Style Fix
```bash
composer cs-fix
```

### Viewing Logs
```bash
tail -f storage/logs/app.log
```

## Security Best Practices

1. **Never commit `.env` file** to version control
2. **Use strong passwords** for all accounts
3. **Keep dependencies updated** with `composer update`
4. **Enable HTTPS** in production
5. **Regular backups** of database and uploads
6. **Monitor logs** for suspicious activity
7. **Change default credentials** immediately

## Deployment

### Production Setup

1. **Set Environment Variables**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

2. **Generate Strong APP_KEY**
```bash
php -r "echo bin2hex(random_bytes(32));"
```

3. **Configure Production Database**
Update database credentials in `.env`

4. **Set File Permissions**
```bash
chmod -R 755 storage/
chmod -R 644 storage/logs/*
```

5. **Enable HTTPS**
Configure SSL certificate on your web server

6. **Set Up Backups**
Configure automated database and file backups

7. **Monitor Application**
Set up log monitoring and error tracking

## API Integration

### M-Pesa Payment Gateway

The application is ready for M-Pesa integration. Configure the following in `.env`:

```env
MPESA_ENV=sandbox
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_PASSKEY=your_passkey
MPESA_SHORTCODE=174379
```

## Troubleshooting

### Common Issues

**Database Connection Failed**
- Check database credentials in `.env`
- Ensure MySQL server is running
- Verify database exists

**Composer Install Fails**
- Update Composer: `composer self-update`
- Check PHP version compatibility
- Verify internet connection

**Routes Not Working**
- Ensure mod_rewrite is enabled (Apache)
- Check `.htaccess` file permissions
- Verify web server configuration

**Session Issues**
- Check `storage/` directory permissions
- Verify session configuration in `.env`
- Clear browser cookies

## Support

For support and questions:
- Email: info@komfort.com
- Phone: +254 XXX XXX XXX

## License

Proprietary - All rights reserved © 2024 Komfort Tours & Travel

## Roadmap

### Phase 1 (Current)
- ✅ Core authentication system
- ✅ User dashboard
- ✅ Basic booking system
- ✅ Database schema
- ✅ Security implementation

### Phase 2 (In Progress)
- 🔄 Complete booking system
- 🔄 Admin dashboard
- 🔄 Payment integration
- 🔄 Vehicle management

### Phase 3 (Planned)
- 📅 Advanced reporting
- 📅 Mobile API
- 📅 Email notifications
- 📅 SMS integration
- 📅 Multi-language support

---

Built with ❤️ for memorable journeys
