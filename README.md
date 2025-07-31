# PHP Blog - Basic CRUD Application

A simple web application built during the ApexPlanet Internship, implementing basic CRUD functionality and user authentication with PHP and MySQL.

## Features

- User Registration & Login (with password hashing)
- Add, edit, delete blog posts
- List all posts (most recent first)
- Only logged-in users can manage posts

## Requirements

- PHP >= 7.x
- MySQL
- XAMPP/WAMP (for local server)
- Web browser

## Setup Instructions

1. **Clone the project or download ZIP**
2. Copy files to your XAMPP `htdocs` folder:
    ```
    C:\xampp\htdocs\myproject
    ```
3. Start **Apache** and **MySQL** using XAMPP Control Panel.
4. Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) and run:
    ```
    CREATE DATABASE blog;

    USE blog;

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL
    );

    CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```
5. Edit `db.php` if your database credentials differ.
6. Access your app at:
    ```
    http://localhost/myproject/register.php
    ```
    Register, log in, and start using the app!

## File Structure

| File              | Purpose                                           |
|-------------------|--------------------------------------------------|
| db.php            | Database connection                              |
| register.php      | User registration                                |
| login.php         | User login                                       |
| logout.php        | End user session                                 |
| index.php         | List, edit, delete posts                         |
| add_post.php      | Add a new blog post                              |
| edit_post.php     | Edit a post                                      |
| delete_post.php   | Delete a post                                    |

## Credentials

- Default MySQL user: `root`
- Password: (leave blank by default)

## Usage

1. Register a user.
2. Login.
3. Add, edit, or delete posts.

## Security Notes

- Passwords are securely hashed.
- Session management restricts access to post management.

## License

This project is for learning and internship submission only.
