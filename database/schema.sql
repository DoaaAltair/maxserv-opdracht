CREATE TABLE IF NOT EXISTS product (
    id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    discount_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    brand VARCHAR(100) NULL,
    thumbnail VARCHAR(500) NULL,
    rating DECIMAL(3, 2) NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    sku VARCHAR(100) NULL,

    PRIMARY KEY (id)
);