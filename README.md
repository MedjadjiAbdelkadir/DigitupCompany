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
## Installation

To run this project, you need to have Composer on your system.

1. Clone this repository: `https://github.com/MedjadjiAbdelkadir/DigitupCompany.gitt`

```cmd
cd DigitupCompany
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:refresh --seed
php artisan serve
```


