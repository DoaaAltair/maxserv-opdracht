# MaxServ B.V. Assignment

A Dockerized PHP application developed for the MaxServ B.V. technical assignment.

The application imports product data from the DummyJSON API, stores the products in a MySQL database, and provides a web-based product catalogue.

The catalogue reads product data from the database instead of requesting the data directly from the external API.

The application is built with plain PHP and does not use a PHP framework.

---

## Overview

The application consists of three main parts:

1. **Product import**
   - Retrieves product data from DummyJSON.
   - Stores the products in MySQL.
   - Supports repeatable imports without creating duplicate products.

2. **Product catalogue**
   - Displays products from the database.
   - Calculates and displays discounted prices.
   - Supports category and brand filtering.
   - Supports sorting by multiple product properties.

3. **Product details**
   - Provides a dedicated page for each product.
   - Displays additional information such as description, stock and pricing.

The project also includes Docker configuration, dependency injection, YAML-based routing, Twig templates, a repository layer, application services and PHPUnit testing.

---

## Features

### Product Import

- Import products from DummyJSON
- Import more than 100 products
- Store products in MySQL
- Repeatable imports
- Update existing products instead of creating duplicates

### Product Catalogue

- Display products from the database
- Product thumbnails
- Product title
- Product brand
- Product category
- Original price
- Discount percentage
- Calculated discounted price
- Euro price formatting

### Filtering and Sorting

- Filter by category
- Filter by brand
- Sort by title
- Sort by price
- Sort by brand
- Sort by category
- Sort by discount percentage
- Ascending sorting
- Descending sorting

### Product Details

- Product image
- Product title
- Product description
- Product brand
- Product category
- Original price
- Discount percentage
- Calculated discounted price
- Stock information
- `404` response for non-existing products

### Technical Features

- Plain PHP
- Dependency Injection
- Repository pattern
- Service layer
- API client abstraction
- YAML-based routing
- Twig templates
- MySQL persistence
- Dockerized environment
- PHPUnit testing

---

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP 8.2 | Application logic |
| Apache | Web server |
| MySQL 8 | Database |
| Docker | Application environment |
| Docker Compose | Service orchestration |
| Twig | Server-side templating |
| Guzzle | HTTP client |
| Symfony Routing | Request routing |
| Symfony DependencyInjection | Dependency injection |
| Symfony Config | Configuration handling |
| Symfony YAML | YAML configuration |
| Symfony HttpFoundation | HTTP handling |
| Symfony Finder | File discovery |
| PHPUnit | Automated testing |

No PHP framework is used.

---

## UI/UX Improvements

The product catalogue was refined with a focus on clarity, consistency and ease of use.

### Catalogue Header

The catalogue uses a compact dark-blue header to create a clear visual introduction without taking unnecessary vertical space.

The header contains:

- Catalogue title
- Short supporting description
- Imported product count

The product count is visually separated from the main content so users can quickly understand the size of the catalogue.

### Floating Filter Bar

The filtering interface was redesigned as a floating panel positioned across the bottom edge of the header.

The filter panel contains:

- Category filter
- Brand filter
- Sort option
- Sort direction
- Reset action when filters are active
- Apply filters action

The floating positioning creates a clear visual connection between the catalogue introduction and the product overview while keeping the filtering controls easy to find.

### Product Cards

The product cards were redesigned to create a consistent visual hierarchy.

Each card contains:

1. Product image
2. Category
3. Discount indicator when applicable
4. Product title
5. Brand
6. Original price
7. Calculated discounted price
8. Link to the product detail page

Cards use consistent spacing, rounded corners, subtle borders and hover interactions.

### Responsive Design

The catalogue layout adapts to different screen sizes.

The product grid changes from four columns on larger screens to fewer columns on smaller screens, while the filter controls also adapt for tablet and mobile layouts.

### Accessibility

Interactive product cards include visible keyboard focus states.

The interface also uses clear text hierarchy, sufficient spacing and consistent interaction states to make the catalogue easier to navigate.

---

## Requirements

The following software is required:

- Docker
- Docker Compose
- Git

Composer is included in the Docker image and does not need to be installed locally.

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/DoaaAltair/maxserv-opdracht.git
cd maxserv-opdracht
```

### 2. Start the application

Build and start the Docker environment:

```bash
docker compose up -d --build
```

The following services are available:

| Service | Address |
|---|---|
| Application | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| MySQL | localhost:3307 |

The application connects to MySQL internally through the Docker service name `db`.

---

## Import Products

The database schema is defined in:

```text
database/schema.sql
```

Run the product importer with:

```bash
docker compose run --rm app php bin/import-products.php
```

The importer retrieves product data from:

```text
https://dummyjson.com/products?limit=0
```

The retrieved products are stored in the MySQL database.

### Repeatable Imports

The DummyJSON product ID is used as the primary key.

The repository uses MySQL's:

```sql
ON DUPLICATE KEY UPDATE
```

This allows the importer to be executed multiple times without creating duplicate products.

When a product already exists, its stored information is updated.

---

## Using the Application

After starting Docker and importing the products, open:

```text
http://localhost:8080
```

The product catalogue retrieves its data from MySQL.

Normal catalogue requests do not request product data directly from DummyJSON.

### Product Catalogue

The catalogue displays:

- Product image
- Product title
- Brand
- Category
- Original price
- Discount percentage
- Calculated discounted price

### Filtering

Products can be filtered by:

- Category
- Brand

The available categories and brands are retrieved from the database.

Brands can also be filtered based on the selected category.

### Sorting

Products can be sorted by:

- Title
- Price
- Brand
- Category
- Discount percentage

Both ascending and descending sorting are supported.

---

## Product Details

Each product can be opened through:

```text
/product?id={id}
```

For example:

```text
/product?id=1
```

The product detail page displays:

- Product image
- Title
- Description
- Brand
- Category
- Original price
- Discount percentage
- Calculated discounted price
- Stock

If the requested product does not exist, the application returns an HTTP `404` response.

---

## Architecture

The application is divided into two packages:

```text
packages/
├── core/
└── app/
```

### Core Package

The `core` package contains reusable application infrastructure:

- Application bootstrap
- Database connection
- Routing
- Twig rendering

### App Package

The `app` package contains functionality specific to the assignment:

- API client
- Controllers
- Product repository
- Import service
- Price calculation service

This separation keeps application-specific functionality separated from the underlying infrastructure.

---

## Application Flow

The application has a single public entry point:

```text
public/index.php
```

The general request flow is:

```text
Browser
   │
   ▼
public/index.php
   │
   ▼
Bootstrap
   │
   ▼
Dependency Injection Container
   │
   ▼
Router
   │
   ▼
Controller
   │
   ├──► Service
   │
   └──► Repository
            │
            ▼
          MySQL
```

### Product Catalogue Flow

```text
GET /
  │
  ▼
IndexController
  │
  ▼
ProductRepository
  │
  ▼
MySQL
  │
  ▼
ProductPriceCalculator
  │
  ▼
Twig Template
  │
  ▼
HTML Response
```

### Product Detail Flow

```text
GET /product?id=1
  │
  ▼
ProductController
  │
  ▼
ProductRepository
  │
  ▼
MySQL
  │
  ▼
ProductPriceCalculator
  │
  ▼
Twig Template
```

---

## Import Architecture

The import process is separated into distinct responsibilities:

```text
bin/import-products.php
        │
        ▼
ImportService
        │
        ▼
DummyJsonClient
        │
        ▼
DummyJSON API
        │
        ▼
ProductRepository
        │
        ▼
MySQL
```

### DummyJsonClient

Responsible for communication with the external DummyJSON API.

### ImportService

Responsible for coordinating the import process.

### ProductRepository

Responsible for storing and retrieving product data from MySQL.

This separation keeps the individual components focused on their own responsibilities.

---

## Database

The application uses MySQL 8.

The main table is:

```text
product
```

The table contains:

| Column | Description |
|---|---|
| `id` | Product identifier |
| `title` | Product title |
| `description` | Product description |
| `category` | Product category |
| `price` | Original price |
| `discount_percentage` | Discount percentage |
| `brand` | Product brand |
| `thumbnail` | Product thumbnail URL |
| `rating` | Product rating |
| `stock` | Available stock |
| `sku` | Product SKU |

The database schema is located at:

```text
database/schema.sql
```

The DummyJSON product ID is used as the primary key.

---

## Price Calculation

Discount calculation is handled by the dedicated:

```text
ProductPriceCalculator
```

The calculation is:

```text
discounted price = price × (1 - discount percentage / 100)
```

The result is rounded to two decimal places.

Example:

```text
Original price:     €100.00
Discount:              20%
Discounted price:    €80.00
```

Keeping this calculation in a dedicated service prevents business logic from being placed directly inside controllers or templates.

---

## Safe Sorting

Sorting parameters are received from the browser but are not inserted directly into the SQL query.

The repository maps supported sorting options to a predefined allowlist.

Supported sorting options are:

```text
title
price
brand
category
discount_percentage
```

This prevents arbitrary column names from being supplied through the request.

---

## Dependency Injection

The application uses Symfony DependencyInjection to manage services.

Dependencies are injected through constructors instead of being instantiated directly inside controllers.

Examples include:

- `ProductRepository`
- `ProductPriceCalculator`
- `TemplateRenderer`
- `DummyJsonClient`

This keeps the classes loosely coupled and makes individual components easier to maintain and test.

---

## Docker

The application runs using Docker Compose.

The environment contains three services.

### Application

The application runs on PHP 8.2 with Apache.

Apache uses:

```text
/var/www/html/public
```

as its document root.

### MySQL

MySQL 8.0 is used as the application's database.

The application connects to the database internally using:

```text
db:3306
```

The database is exposed on the host through port `3307`.

### phpMyAdmin

phpMyAdmin is included for convenient database inspection during development.

It is available at:

```text
http://localhost:8081
```

---

## Composer

Composer is used for dependency management.

The main dependencies include:

- `guzzlehttp/guzzle` — HTTP client
- `symfony/routing` — routing
- `symfony/config` — configuration
- `symfony/yaml` — YAML configuration
- `symfony/http-foundation` — HTTP handling
- `symfony/dependency-injection` — dependency injection
- `symfony/finder` — filesystem discovery
- `twig/twig` — template rendering
- `phpunit/phpunit` — automated testing

The application and core packages use PSR-4 autoloading.

---

## Testing

The project uses PHPUnit for automated testing.

Run the test suite with:

```bash
docker compose run --rm app vendor/bin/phpunit
```

The current test verifies the product discount calculation.

The PHPUnit configuration is located at:

```text
phpunit.xml
```

The test is located at:

```text
tests/ProductPriceCalculatorTest.php
```

Current result:

```text
OK (1 test, 1 assertion)
```

---

## Project Structure

```text
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
```

---

## Technical Decisions

### Database as the Source of Truth

The external API is used during the import process only.

The web application reads product data from MySQL. Normal catalogue requests therefore do not depend on the availability of DummyJSON.

### Separation of Responsibilities

API communication, importing, database access and price calculation are implemented in separate classes.

This keeps controllers small and makes the individual components easier to maintain.

### Dependency Injection

Dependencies are provided through the Symfony Dependency Injection container instead of being instantiated directly inside controllers.

### Repeatable Imports

The product ID is used as the primary key and the repository uses an upsert strategy.

This makes the import process repeatable without creating duplicate products.

### Restricted Sorting

Sorting values are validated against an allowlist before being used in the SQL query.

This prevents arbitrary column names from being passed through the request.

---

## Useful Commands

### Start the application

```bash
docker compose up -d --build
```

### Stop the application

```bash
docker compose down
```

### Import products

```bash
docker compose run --rm app php bin/import-products.php
```

### Run tests

```bash
docker compose run --rm app vendor/bin/phpunit
```

### Check Docker containers

```bash
docker compose ps
```

### Check Git status

```bash
git status
```

---

## License

This project was created as an implementation of the MaxServ B.V. technical assignment.
