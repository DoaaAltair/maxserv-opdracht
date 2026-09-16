# MaxServ B.V. Assignment

A Dockerized PHP application built as part of the MaxServ B.V. technical assignment.

The application imports products from the DummyJSON API, stores them in a MySQL database and provides a web-based product catalogue that reads the data from the database.

The implementation uses plain PHP without a PHP framework. External PHP packages are used where appropriate.

---

## Features

The application currently includes:

- Import of products from DummyJSON
- Database persistence using MySQL
- Product overview based on database data
- Product detail pages
- Standard product price
- Calculated discounted price
- Euro price formatting
- Product thumbnails
- Category filtering
- Brand filtering
- Sorting by:
  - Title
  - Price
  - Brand
  - Category
  - Discount percentage
- Ascending and descending sorting
- Dependency Injection using Symfony DependencyInjection
- YAML-based routing
- Twig templates
- PHPUnit test for the price calculation
- Dockerized application environment
- phpMyAdmin for database inspection

---

## Requirements

To run the project, you need:

- Docker
- Docker Compose
- Git

Composer is included in the Docker image, so installing Composer locally is not required.

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/DoaaAltair/maxserv-opdracht.git
cd maxserv-opdracht
2. Start the Docker environment
docker compose up -d --build

The following services will be started:

Service	URL
Application	http://localhost:8080
phpMyAdmin	http://localhost:8081
MySQL	localhost:3307

The application uses MySQL internally through the Docker service name db.

Import Products

The database schema is created from:

database/schema.sql

Products can be imported from DummyJSON using the provided CLI command:

docker compose run --rm app php bin/import-products.php

The importer retrieves the products from:

https://dummyjson.com/products?limit=0

and stores them in the MySQL database.

The import process uses an ON DUPLICATE KEY UPDATE strategy, which makes the import command safe to run multiple times without creating duplicate products.

Application

After starting Docker and importing the products, open:

http://localhost:8080

The product catalogue is loaded from the MySQL database.

The browser does not request product data directly from DummyJSON.

Product catalogue

The overview displays:

Product thumbnail
Title
Brand
Category
Original price
Discount percentage
Calculated discounted price

Products can be filtered and sorted directly from the catalogue.

Product details

Clicking a product opens:

/product?id={id}

The detail page displays additional information such as:

Description
Brand
Category
Original price
Discount percentage
Discounted price
Stock
Product image
Architecture

The project is divided into a small core package and an application package.

packages/
├── core/
│   ├── config/
│   └── src/
│       ├── Database/
│       ├── Render/
│       ├── Routing/
│       └── Bootstrap.php
│
└── app/
    ├── config/
    └── src/
        ├── Api/
        ├── Controller/
        ├── Repository/
        └── Service/
Core

The core package contains reusable application infrastructure:

Database connection
Dependency Injection bootstrap
Routing
Twig rendering
App

The app package contains assignment-specific functionality:

Controllers
Product repository
DummyJSON API client
Product import service
Product price calculation

This separation keeps the application logic independent from the basic infrastructure.

Application Flow

The application has a single public entry point:

public/index.php

The flow is:

Browser
   ↓
public/index.php
   ↓
Bootstrap
   ↓
Dependency Injection Container
   ↓
Router
   ↓
Controller
   ↓
Service / Repository
   ↓
MySQL

For example, when opening the product catalogue:

GET /
  ↓
IndexController
  ↓
ProductRepository
  ↓
MySQL
  ↓
ProductPriceCalculator
  ↓
Twig template
  ↓
HTML response

For a product detail page:

GET /product?id=1
  ↓
ProductController
  ↓
ProductRepository
  ↓
MySQL
  ↓
ProductPriceCalculator
  ↓
Twig
Import Architecture

The import process is separated into different responsibilities.

bin/import-products.php
        ↓
ImportService
        ↓
DummyJsonClient
        ↓
DummyJSON API
        ↓
ProductRepository
        ↓
MySQL
DummyJsonClient

Responsible only for communicating with the external API.

ImportService

Responsible for coordinating the import process.

ProductRepository

Responsible for persisting product data in the database.

This separation makes each part easier to test and maintain.

Database

The application uses MySQL 8.

The main table is:

product

The table stores information including:

id
title
description
category
price
discount_percentage
brand
thumbnail
rating
stock
sku

The database schema is located at:

database/schema.sql

The product ID from DummyJSON is used as the primary key.

Price Calculation

Discount calculation is handled by a dedicated service:

ProductPriceCalculator

The calculation is:

discounted price = price × (1 - discount percentage / 100)

The result is rounded to two decimal places.

For example:

Original price:       €100.00
Discount:                 20%
Discounted price:      €80.00

Keeping this calculation in a separate service prevents business logic from being mixed into the controller or template.

Filtering and Sorting

The product catalogue supports filtering by:

Category
Brand

It also supports sorting by:

Title
Price
Brand
Category
Discount percentage

Sorting direction can be:

Ascending
Descending

User-controlled sorting is restricted to a predefined list of allowed database columns. This prevents arbitrary column names from being inserted into the SQL query.

Testing

The project uses PHPUnit for automated testing.

Run the test suite with:

docker compose run --rm app vendor/bin/phpunit

Current test coverage includes the product price calculation.

Example:

OK (1 test, 1 assertion)

The test configuration is located in:

phpunit.xml

Tests are located in:

tests/
Docker

The application runs using Docker Compose.

The environment contains:

Application

PHP 8.2 with Apache.

Apache is configured to use:

/var/www/html/public

as its document root.

Database

MySQL 8.0.

phpMyAdmin

phpMyAdmin is included to make database inspection easier during development.

The application communicates with MySQL using the Docker service name:

db

rather than localhost.

Composer

Composer is used for dependency management.

The project uses external packages including:

guzzlehttp/guzzle — HTTP client
symfony/routing — routing
symfony/config — configuration
symfony/yaml — YAML configuration
symfony/http-foundation — HTTP foundation
symfony/dependency-injection — dependency injection
symfony/finder — filesystem discovery
twig/twig — template rendering
phpunit/phpunit — automated testing

No PHP framework is used.

The application-specific and core packages are registered through Composer PSR-4 autoloading.

Project Structure
.
├── bin/
│   └── import-products.php
│
├── config/
│   └── routes.yaml
│
├── database/
│   └── schema.sql
│
├── packages/
│   ├── app/
│   │   ├── config/
│   │   │   └── services.yaml
│   │   └── src/
│   │       ├── Api/
│   │       │   └── DummyJsonClient.php
│   │       ├── Controller/
│   │       │   ├── IndexController.php
│   │       │   └── ProductController.php
│   │       ├── Repository/
│   │       │   └── ProductRepository.php
│   │       └── Service/
│   │           ├── ImportService.php
│   │           └── ProductPriceCalculator.php
│   │
│   └── core/
│       ├── config/
│       │   └── services.yaml
│       └── src/
│           ├── Database/
│           │   └── Connection.php
│           ├── Render/
│           │   └── TemplateRenderer.php
│           ├── Routing/
│           │   └── Router.php
│           └── Bootstrap.php
│
├── public/
│   ├── css/
│   │   └── style.css
│   ├── .htaccess
│   └── index.php
│
├── templates/
│   ├── index.html.twig
│   └── product.html.twig
│
├── tests/
│   └── ProductPriceCalculatorTest.php
│
├── composer.json
├── composer.lock
├── docker-compose.yml
├── Dockerfile
└── phpunit.xml
Technical Decisions

A few deliberate technical choices were made during the implementation.

Database as the source for the catalogue

The external API is used only during the import process.

The web application reads products from MySQL. This means the catalogue does not depend on the availability of DummyJSON for every page request.

Separation of responsibilities

API communication, importing, database access and price calculation are implemented in separate classes.

This keeps controllers relatively small and makes the code easier to change.

Dependency Injection

Services and repositories are injected through the Symfony Dependency Injection container rather than being instantiated directly inside controllers.

Safe sorting

Sorting parameters received from the browser are mapped against an allowlist before being added to the SQL query.

Repeatable imports

The product ID is used as the primary key and the repository uses an upsert strategy. Running the importer again updates existing products instead of creating duplicates.

Useful Commands
Start the application
docker compose up -d --build
Stop the application
docker compose down
Import products
docker compose run --rm app php bin/import-products.php
Run tests
docker compose run --rm app vendor/bin/phpunit
Check Git status
git status
