# Volunteer & Events Management System

A full-featured system for managing volunteers and events, including automatic matching of volunteers to events. The system provides a modern frontend built with Vue.js and a backend API built with PHP and MySQL.

---

## Features

- **Volunteer Management**: Add, edit, delete, and view volunteers.
- **Event Management**: Add, edit, delete, and view events.
- **Automatic Matching System**: Match volunteers to events based on skills and availability.
- **Dashboard & Statistics**: View summaries and latest activities.
- **Modern Frontend**: Built with Vue.js, Tailwind CSS, and responsive design.
- **Backend API**: PHP-based RESTful API with MySQL database.

---

## Requirements

- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- **Node.js** 14 or higher
- **Composer** (for PHP dependencies)
- **npm** or **yarn** (for frontend dependencies)

---

## Installation

### Backend Setup

1. Clone the repository:
```bash
git clone <https://github.com/EmranAsemSaeed/Test_Vue-PHP.git>
cd project-root/backend


2.Install PHP dependencies (if any)
composer install


3.Configure the database:
A.Create a MySQL database:
   CREATE DATABASE vue_php;


B.Update database credentials in backend/config.php:
DB_HOST=localhost
DB_NAME=vue_php
DB_USER=root
DB_PASS=


4.Import initial database schema:
php -S localhost:8000 -t backend/public


Frontend Setup
1.Navigate to frontend folder:
cd ../frontend/vue_ptoject

2.Install dependencies:
npm install
# or
yarn install


3.Configure API URL in .env file:
VITE_API_BASE_URL=http://localhost:8000


4.Start the development server:
npm run dev
# or
yarn dev


5.Open the frontend in your browser:
http://localhost:8000


Project Structure
project-root/
├─ backend/             # PHP backend
│  ├─ api/              # API endpoints
│  ├─ App/
│  │  ├─ Controllers/   # Controllers
│  │  ├─ Models/        # Database models
│  │  └─ Core/          # Router and core classes
│  └─ config.php        # Database configuration
├─ frontend/            # Vue.js frontend
│  ├─ src/
│  │  ├─ components/    # Vue components (forms, modals, tables)
│  │  ├─ pages/         # Vue pages (Home, Volunteers, Events, Matches)
│  │  ├─ router/        # Vue Router configuration
│  │  └─ services/      # API services
│  └─ vite.config.js
├─ README.md            # Project documentation
└─ .env                 # Environment variables
