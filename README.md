# 🏠 Real Estate Platform

A modern, full-featured real estate management platform built with Laravel 10, Livewire, and Tailwind CSS.

## 🌟 Features

- **Property Listings** - Browse and manage real estate properties
- **Search & Filter** - Advanced search with multiple filter options
- **Property Details** - Comprehensive property information with images
- **User Authentication** - Secure login and registration
- **Agent Management** - Manage real estate agents and their listings
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-----------|
| **Backend** | Laravel 10 |
| **Frontend** | Blade Templates, Alpine.js, Tailwind CSS 4 |
| **Build Tool** | Vite |
| **Database** | MySQL |
| **Real-time** | Livewire 3.7 |
| **Auth** | Laravel Sanctum |
| **Testing** | Pest PHP |

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & npm
- MySQL 8.0+
- Git

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/silwalrajaram09/realEstate.git
cd realEstate
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install JavaScript Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=real_estate
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🔧 Development

### Development Server with Hot Module Reload
```bash
npm run dev
# In another terminal
php artisan serve
```

### Run Tests
```bash
./vendor/bin/pest
```

### Code Formatting
```bash
./vendor/bin/pint
```

### Database Migrations
```bash
# Create a new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback
```

## 📁 Project Structure

```
realEstate/
├── app/                      # Application code
│   ├── Models/              # Eloquent models
│   ├── Http/                # Controllers, requests, middleware
│   └── Services/            # Business logic
├── resources/               # Views and assets
│   ├── views/              # Blade templates
│   └── css/                # Tailwind CSS
├── routes/                 # Application routes
│   ├── web.php            # Web routes
│   └── api.php            # API routes
├── database/              # Migrations and seeders
├── config/                # Configuration files
├── storage/               # File storage (logs, uploads)
└── tests/                 # Test files
```

## 🔐 Security

- User authentication via Laravel Sanctum
- CSRF protection on all forms
- SQL injection prevention with Eloquent ORM
- Regular security updates via Composer

## 📚 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com)
- [Tailwind CSS Documentation](https://tailwindcss.com)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💼 Author

**Rajaram Silwal**
- GitHub: [@silwalrajaram09](https://github.com/silwalrajaram09)

## 📞 Support

For support, email rajaramsilwal819@gmail.com or open an issue on GitHub.

---

Made with ❤️ by the Real Estate Team
