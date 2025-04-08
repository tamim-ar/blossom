# Blossom Flower Shop

A full-featured online flower shop web application built with PHP and MySQL.

## Features

### Customer Features
- Browse and search flower catalog
- Shopping cart functionality
- User registration and authentication
- Order tracking
- Profile management
- Order history

### Admin Features
- Product management (CRUD operations)
- Order management
- User management
- Admin management
- Dashboard with statistics

## Technical Stack

- **Frontend**: HTML5, TailwindCSS, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (XAMPP)

## Prerequisites

1. XAMPP (with PHP 7.4 or higher)
2. Web browser (Chrome/Firefox recommended)
3. Text editor (VSCode recommended)

## Installation

1. **XAMPP Setup**
   - Download and install XAMPP from https://www.apachefriends.org/
   - Start Apache and MySQL services from XAMPP Control Panel

2. **Project Setup**
   ```bash
   # Clone or download the project to XAMPP's htdocs folder
   cd c:/xampp/htdocs
   git clone [repository-url] blossom-flower-shop
   # Or manually extract the project files to this location
   ```

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named 'blossom_db'
   - Import the database schema from `database/blossom_db.sql`

4. **File Structure Setup**
   - Create required directories:
     ```
     mkdir assets/images
     ```
   - Add flower images to `assets/images` directory (*.jpg format)
   - Ensure proper permissions on directories

## Project Structure

```
blossom-flower-shop/
├── admin/                 # Admin panel files
│   ├── dashboard.php     
│   ├── products.php
│   ├── orders.php
│   ├── users.php
│   └── admins.php
├── api/                  # API endpoints
│   └── cart.php
├── assets/              # Static resources
│   ├── images/         # Product images
│   └── js/             # JavaScript files
├── auth/               # Authentication files
│   ├── login.php
│   ├── signup.php
│   └── logout.php
├── config/             # Configuration files
│   └── database.php
├── includes/           # Reusable components
│   ├── navbar.php
│   └── footer.php
└── database/          # Database schema
    └── blossom_db.sql
```

## Configuration

1. **Database Configuration**
   - Edit `config/database.php`:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'blossom_db');
     ```

2. **Default Admin Account**
   - Username: admin
   - Email: admin@blossom.com
   - Password: admin123

## Running the Application

1. Start XAMPP services (Apache and MySQL)
2. Open your browser and navigate to:
   - Shop: http://localhost/blossom-flower-shop
   - Admin: http://localhost/blossom-flower-shop/admin

## Development Guidelines

1. **Coding Standards**
   - Follow PHP PSR-12 coding standards
   - Use meaningful variable and function names
   - Comment complex logic

2. **Security Practices**
   - Sanitize all user inputs
   - Use prepared statements for database queries
   - Implement proper authentication checks
   - Validate file uploads

3. **Database**
   - Use transactions for critical operations
   - Implement proper indexing
   - Follow naming conventions

## Troubleshooting

1. **Database Connection Issues**
   - Verify XAMPP services are running
   - Check database credentials in config file
   - Run test.php to verify connection

2. **Image Upload Issues**
   - Verify folder permissions
   - Check file size limits in PHP configuration
   - Ensure proper file types

3. **404 Errors**
   - Verify .htaccess configuration
   - Check file paths and permissions
   - Ensure mod_rewrite is enabled

## Contributors

- Tamim Ahasan Rijon

## Support

For support and queries:
- Email: tamimahasan.ar@gmail.com
- Phone: +880 1611-621958