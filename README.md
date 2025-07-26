# Fruitables Store

A complete e-commerce solution for fruit and vegetable stores.

## Features

- Customer registration and login
- Product catalog with categories
- Shopping cart functionality
- Secure checkout with multiple payment methods
- Order management system
- Admin panel for product and order management
- Email notifications
- Responsive design

## Installation

1. Upload files to your web server
2. Set the web root to the `public` directory
3. Import `app/database_setup.sql` to your database
4. Configure database settings in `app/config/database.php`
5. Configure email settings in `app/config/email.php`
6. Set up your domain in the configuration files

## Directory Structure

```
fruitables-store/
├── public/           # Web root (publicly accessible)
│   ├── index.php    # Main entry point
│   ├── assets/      # CSS, JS, images
│   └── admin/       # Admin panel
├── app/             # Application logic (not public)
│   ├── config/      # Configuration files
│   ├── includes/    # PHP includes
│   ├── classes/     # PHP classes
│   └── uploads/     # File uploads
├── logs/            # Application logs
├── backups/         # Database backups
└── .env.example     # Environment variables template
```

## Security

- All sensitive files are outside the web root
- Database credentials are in protected config files
- Input validation and sanitization implemented
- CSRF protection enabled
- XSS protection headers configured

## Support

For support, please contact the development team.
