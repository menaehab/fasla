<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
<img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
<img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
<img src="https://img.shields.io/badge/API-RESTful-009688?style=for-the-badge" alt="RESTful API">
</p>

# 🛒 E-Commerce API Platform

A complete, production-ready E-Commerce RESTful API built with Laravel 11. Features multi-role authentication, product management, shopping cart system, order processing, and comprehensive admin dashboard capabilities.

---

## 📋 Table of Contents

- [Features](#-features)
- [Quick Start](#-quick-start)
- [System Architecture](#-system-architecture)
- [API Documentation](#-api-documentation)
- [Authentication & Authorization](#-authentication--authorization)
- [Database Schema](#-database-schema)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Contributing](#-contributing)

---

## ✨ Features

### 🔐 Authentication & Authorization
- **Multi-Guard Authentication** (Users, Sellers, Admins)
- **Laravel Sanctum** for API token authentication
- **Role-Based Access Control** (Spatie Permission)
- Separate login endpoints for customers and dashboard users

### 👥 User Management
- User registration and authentication
- Profile management
- Order history tracking
- Shopping cart persistence

### 🏪 Seller Management
- Seller registration and authentication
- Store profile management
- Product inventory management
- Order fulfillment tracking
- Sales analytics

### 📦 Product Management
- CRUD operations for products
- Product categories and subcategories
- Multiple product images
- Product variants (colors, sizes)
- Stock management
- Price and discount management
- SEO-friendly slugs

### 🛍️ Shopping Cart System
- Add/update/remove items
- Persistent cart storage
- Real-time stock validation
- Auto-remove unavailable products
- Price calculation with discounts
- Cart validation before checkout

### 📋 Order Management
- Create orders from cart
- Order status tracking (Pending, Processing, Shipped, Delivered, Cancelled)
- Order history for users
- Order management for sellers
- Admin order oversight
- Automatic stock deduction

### 🎨 Categories & Organization
- Hierarchical category structure
- Subcategories support
- Category-based product filtering
- SEO-friendly category slugs

### 🔧 Admin Dashboard
- Complete system oversight
- User management
- Seller management
- Category management
- Order management
- Product oversight

---

## 🚀 Quick Start

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd <project-directory>

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 6. Run migrations and seeders
php artisan migrate
php artisan db:seed

# 7. Start the development server
php artisan serve

# 8. (Optional) Compile frontend assets
npm run dev
```

### 🔑 Default Test Credentials

After running seeders, you can use these credentials:

**Customer Account:**
- Email: `test@example.com`
- Password: `password123`

**Seller/Admin Account:**
- Email: `seller@example.com`
- Password: `password123`

### 🧪 Test the System

Open the interactive test page:
```
http://localhost:8000/cart-test.html
```

---

## 🏗️ System Architecture

### Technology Stack

| Component | Technology |
|-----------|-----------|
| **Framework** | Laravel 11 |
| **Language** | PHP 8.2+ |
| **Database** | MySQL/MariaDB |
| **Authentication** | Laravel Sanctum |
| **Authorization** | Spatie Permission |
| **API Style** | RESTful |
| **Slugs** | Spatie Sluggable |

### Project Structure

```
├── app/
│   ├── Enum/
│   │   └── OrderStatusEnum.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AdminController.php
│   │   │       ├── AuthController.php
│   │   │       ├── CartController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── OrderController.php
│   │   │       ├── ProductController.php
│   │   │       ├── SellerController.php
│   │   │       ├── SubCategoryController.php
│   │   │       └── UserController.php
│   │   ├── Requests/
│   │   │   ├── AddToCartRequest.php
│   │   │   ├── CreateOrderFromCartRequest.php
│   │   │   ├── LoginRequest.php
│   │   │   ├── ProductRequest.php
│   │   │   ├── RegisterRequest.php
│   │   │   ├── StoreCategoryRequest.php
│   │   │   ├── StoreOrderRequest.php
│   │   │   ├── UpdateCartItemRequest.php
│   │   │   └── ... (more requests)
│   │   └── Resources/
│   │       ├── CartResource.php
│   │       ├── CartItemResource.php
│   │       ├── CategoryResource.php
│   │       ├── OrderResource.php
│   │       ├── ProductResource.php
│   │       └── ... (more resources)
│   ├── Models/
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Product.php
│   │   ├── ProductColor.php
│   │   ├── ProductImage.php
│   │   ├── ProductSize.php
│   │   ├── Seller.php
│   │   ├── SubCategory.php
│   │   └── User.php
│   ├── Observers/
│   │   └── ProductObserver.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
└── public/
    ├── cart-test.html
    └── images/
```

---

## 📡 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints

#### Register User
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "01234567890",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login (Customer)
```http
POST /api/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password123"
}

Response:
{
  "token": "1|xxxxxxxxxxxxx",
  "user": { ... }
}
```

#### Login (Dashboard - Seller/Admin)
```http
POST /api/dashboard/login
Content-Type: application/json

{
  "email": "seller@example.com",
  "password": "password123"
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

---

### Product Endpoints

#### Get Products by SubCategory
```http
GET /api/products/{subcategory-slug}
Authorization: Bearer {token}
```

#### Get Single Product
```http
GET /api/product/{product-slug}
Authorization: Bearer {token}
```

#### Create Product (Seller/Admin)
```http
POST /api/dashboard/products
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "name": "Product Name",
  "description": "Product description",
  "price": 100.00,
  "discounted_price": 90.00,
  "quantity": 50,
  "sub_category_id": 1,
  "images[]": [file1, file2],
  "colors[]": ["Red", "Blue"],
  "sizes[]": ["S", "M", "L"]
}
```

#### Update Product
```http
PUT /api/dashboard/products/{product-slug}
Authorization: Bearer {token}
```

#### Delete Product
```http
DELETE /api/dashboard/products/{product-slug}
Authorization: Bearer {token}
```

---

### Cart Endpoints

#### Get Cart
```http
GET /api/cart
Authorization: Bearer {token}

Response:
{
  "data": {
    "id": 1,
    "items": [...],
    "total_price": 200.00,
    "can_checkout": true
  }
}
```

#### Add to Cart
```http
POST /api/cart
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 2
}
```

#### Update Cart Item
```http
PUT /api/cart/{cart_item_id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "quantity": 3
}
```

#### Remove from Cart
```http
DELETE /api/cart/{cart_item_id}
Authorization: Bearer {token}
```

#### Clear Cart
```http
DELETE /api/cart-clear
Authorization: Bearer {token}
```

#### Validate Cart
```http
GET /api/cart/validate
Authorization: Bearer {token}
```

---

### Order Endpoints

#### Create Order from Cart
```http
POST /api/orders/from-cart
Authorization: Bearer {token}
Content-Type: application/json

{
  "shipping_address": "123 Main St, City, Country"
}
```

#### Get My Orders
```http
GET /api/my-orders
Authorization: Bearer {token}
```

#### Get Single Order
```http
GET /api/orders/{order-slug}
Authorization: Bearer {token}
```

#### Update Order Status (Seller/Admin)
```http
PUT /api/dashboard/orders/{order-slug}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "processing"
}
```

#### Get All Orders (Seller/Admin)
```http
GET /api/dashboard/orders
Authorization: Bearer {token}
```

---

### Category Endpoints

#### Get All Categories
```http
GET /api/categories
Authorization: Bearer {token}
```

#### Get SubCategories by Category
```http
GET /api/sub-categories/{category-slug}
Authorization: Bearer {token}
```

#### Create Category (Admin)
```http
POST /api/dashboard/categories
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Electronics",
  "description": "Electronic devices"
}
```

#### Update Category (Admin)
```http
PUT /api/dashboard/categories/{category-slug}
Authorization: Bearer {token}
```

#### Delete Category (Admin)
```http
DELETE /api/dashboard/categories/{category-slug}
Authorization: Bearer {token}
```

---

### Admin Endpoints

#### Manage Users
```http
GET    /api/dashboard/users
POST   /api/dashboard/users
PUT    /api/dashboard/users/{user-slug}
DELETE /api/dashboard/users/{user-slug}
Authorization: Bearer {admin-token}
```

#### Manage Sellers
```http
GET    /api/dashboard/sellers
POST   /api/dashboard/sellers
PUT    /api/dashboard/sellers/{seller-slug}
DELETE /api/dashboard/sellers/{seller-slug}
Authorization: Bearer {admin-token}
```

#### Manage Admins
```http
GET    /api/dashboard/admins
POST   /api/dashboard/admins
PUT    /api/dashboard/admins/{admin-slug}
DELETE /api/dashboard/admins/{admin-slug}
Authorization: Bearer {admin-token}
```

---

## 🔐 Authentication & Authorization

### Guards

The system uses multiple authentication guards:

1. **web** - For regular users/customers
2. **seller** - For sellers and admins

### Roles & Permissions

Using Spatie Permission package:

- **Admin** - Full system access
- **Seller** - Manage own products and orders
- **User** - Browse, cart, and order

### Middleware

- `auth:sanctum` - Authenticate users
- `auth:seller` - Authenticate sellers/admins
- `role:admin` - Admin-only access

---

## 🗄️ Database Schema

### Core Tables

#### users
```sql
- id
- name
- slug (unique)
- email (unique)
- phone
- password
- email_verified_at
- remember_token
- timestamps
```

#### sellers
```sql
- id
- name
- slug (unique)
- store_name
- address
- email (unique)
- phone
- password
- email_verified_at
- remember_token
- timestamps
```

#### products
```sql
- id
- name
- slug (unique)
- description
- price
- discounted_price
- quantity
- seller_id (FK)
- sub_category_id (FK)
- timestamps
```

#### categories
```sql
- id
- name
- slug (unique)
- description
- timestamps
```

#### sub_categories
```sql
- id
- name
- slug (unique)
- description
- category_id (FK)
- timestamps
```

#### carts
```sql
- id
- user_id (FK)
- timestamps
```

#### cart_items
```sql
- id
- cart_id (FK, cascade)
- product_id (FK, cascade)
- quantity
- price
- timestamps
```

#### orders
```sql
- id
- slug (unique)
- user_id (FK)
- total_price
- status (enum)
- shipping_address
- timestamps
```

#### order_items
```sql
- id
- order_id (FK)
- product_id (FK)
- seller_id (FK)
- quantity
- price
- color
- size
- timestamps
```

### Relationships

- User → Cart (1:1)
- User → Orders (1:N)
- Seller → Products (1:N)
- Seller → OrderItems (1:N)
- Category → SubCategories (1:N)
- SubCategory → Products (1:N)
- Product → ProductImages (1:N)
- Product → ProductColors (1:N)
- Product → ProductSizes (1:N)
- Product → CartItems (1:N)
- Cart → CartItems (1:N)
- Order → OrderItems (1:N)

---

## 🧪 Testing

### Interactive Web Interface

Open the test page:
```
http://localhost:8000/cart-test.html
```

Features:
- User authentication
- Product browsing
- Cart management
- Order creation
- Real-time API responses

### Postman Collection

Import the provided collection:
```
Cart_API_Postman_Collection.json
```

Includes:
- All API endpoints
- Auto token management
- Pre-configured requests
- Example responses

### Manual Testing

```bash
# Run migrations with fresh database
php artisan migrate:fresh --seed

# Test specific seeder
php artisan db:seed --class=CartTestSeeder

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# View all routes
php artisan route:list
```

---

## 📚 Additional Documentation

| Document | Description |
|----------|-------------|
| [START_HERE.md](START_HERE.md) | Quick start guide (3 minutes) |
| [CART_SYSTEM_README.md](CART_SYSTEM_README.md) | Complete cart system documentation |
| [CART_API_DOCUMENTATION.md](CART_API_DOCUMENTATION.md) | Detailed cart API docs |
| [CART_TEST_GUIDE.md](CART_TEST_GUIDE.md) | Testing scenarios and guides |
| [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) | Implementation details |
| [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md) | Documentation index |
| [COMMANDS.txt](COMMANDS.txt) | Quick command reference |

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate new `APP_KEY`
- [ ] Configure production database
- [ ] Set up proper file permissions
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate
- [ ] Configure queue workers
- [ ] Set up scheduled tasks (cron)
- [ ] Configure backup system
- [ ] Set up monitoring and logging

### Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## 🛠️ Development

### Code Style

This project follows PSR-12 coding standards.

```bash
# Format code
./vendor/bin/pint
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=CartTest
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Spatie](https://spatie.be) - Laravel Packages
- [Laravel Sanctum](https://laravel.com/docs/sanctum) - API Authentication

---

## 📞 Support

For issues, questions, or contributions:

- Check the [documentation](DOCUMENTATION_INDEX.md)
- Review [common issues](COMMANDS.txt)
- Check Laravel logs: `storage/logs/laravel.log`

---

<p align="center">
<strong>Built with ❤️ using Laravel 11</strong>
</p>

<p align="center">
<sub>E-Commerce API Platform - Complete, Scalable, Production-Ready</sub>
</p>
