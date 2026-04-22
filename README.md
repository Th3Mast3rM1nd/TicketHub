# 🎫 TicketHub – Modul 151 Web Application

A secure ticket system web application built with **PHP**, **MySQL** and **Apache2**.  
Users can register, log in, write tickets visible to everyone, and manage their own tickets.  
Admins have full control over all tickets on the platform.

---

## 📋 Table of Contents

- [Project Description](#project-description)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation on Apache2 (Ubuntu)](#installation-on-apache2-ubuntu)
- [Project Structure](#project-structure)
- [User Roles](#user-roles)
- [Security Features (Modul 151)](#security-features-modul-151)
- [HTML Validation](#html-validation)
- [Git & Version Control](#git--version-control)
- [Author](#author)

---

## Project Description

**TicketHub** is a ticket system platform where:

- Anyone can register a personal account
- Registered users can log in and write tickets
- All tickets are visible to every logged-in user
- Each user can only delete their own tickets
- An admin account can delete any ticket from any user
- Users can change their own password at any time

This project demonstrates the security concepts of **Modul 151** in a realistic, easy-to-explain web application.

---

## Features

| Feature | Who can use it |
|---|---|
| Register an account | Everyone (public) |
| Log in | Registered users |
| Write a ticket | Logged-in users |
| View all tickets | Logged-in users |
| Delete own tickets | Logged-in users |
| Delete any ticket | Admin only |
| Change password | Logged-in users |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Web Server | Apache2 (Ubuntu) |
| Backend | PHP 8.x |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| Versioning | Git + GitHub |

---

## Requirements

- Ubuntu with Apache2 installed and running
- PHP 8.0+ with extension: `php-mysql`
- MySQL or MariaDB
- Modern browser (Chrome, Firefox, Edge)

---
