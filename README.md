# DigitupCompany API

## Description
DigitupCompany RESTful API clone.

## Technologies Used 
- Laravel
- PHP
- MySQL 

## Feature
  - Authentication
  - Task Management

## Requirement
- [php version 7.3.0](https://www.php.net)
- [composer](https://getcomposer.org)

## How To Use

### Download Repository

```bash
# Clone this repository
$ git clone https://github.com/MedjadjiAbdelkadir/DigitupCompany.gitt`
# Go to the project directory
$ cd DigitupCompany
# Create file .env
$ cp .env.example .env.
# Create file .env.testing
$ cp .env.example .env.testing

# Generate Key Of .env
$ php artisan key:generate.
```

### Create DataBase && Migration && Seeding
```bash
# Create DataBase
$ CREATE DATABASE IF NOT EXISTS 'digitupcompany'
# Go to file .env
DB_DATABASE=digitupcompany
# Migration Table
$ php artisan migrate
# Seeding table
$ php artisan db:seed
```

### Run Project

```bash
# Run the project
$ php artisan serve
```

### Testing 
```bash
# Go to file .env.testing

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# Test Auth
$ php artisan test --filter=AuthApiTest

# Test Task
$ php artisan test --filter=TaskApiTest
```