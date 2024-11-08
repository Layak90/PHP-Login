# 🔐 PHP Login System with Authentication

## Overview

A simple, secure, and modern PHP login system with user registration and authentication, featuring responsive design and smooth animations.

## 🌟 Features

- User registration
- User login
- Session management
- Password hashing
- Error handling
- Responsive design
- Smooth form transitions
- Secure database interactions

## 🛠 Technologies Used

- PHP 7.4+
- MySQL
- HTML5
- CSS3
- JavaScript
- PDO/MySQLi

## 📋 Prerequisites

- PHP 7.4 or higher
- MySQL Database
- Web Server (Apache/Nginx)
- Web Browser

## 📱 Responsive Design
The login system is fully responsive and works on:

- Desktop browsers
- Tablets
- Mobile devices

## 🧪 Testing

- User Registration
- Open the login page
- Click "Register"
- Enter username and password
- Submit the form

## 🚀 Installation

### 1. SQL 

```SQL
CREATE DATABASE login_system;
USE login_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

### 2. Clone the Repository

```bash
git clone https://github.com/layak90/php-login-system.git
cd php-login-system

