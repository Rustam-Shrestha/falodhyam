CREATE DATABASE falodhyam_buyer;
USE buyerside;

CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(10) NOT NULL,
    address VARCHAR(255) NOT NULL,
    house_number VARCHAR(8) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2), -- Use DECIMAL for price to handle floating-point precision
    image VARCHAR(255),
    product_detail TEXT,
    type ENUM('Berries', 'Drupes', 'Pomes', 'Citrus Fruits', 'Melons', 'Dried Fruits', 'Tropical Fruits', 'Others')
);

CREATE TABLE cart (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36),
    product_id VARCHAR(36),
    price DECIMAL(10, 2), -- Use DECIMAL for price to handle floating-point precision
    qty INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
    on delete cascade
);

CREATE TABLE orders (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36),
    name VARCHAR(255),
    number VARCHAR(255),
    email VARCHAR(255),
    address VARCHAR(255),
    house_number VARCHAR(8) NOT NULL,
    method VARCHAR(255),
    product_id VARCHAR(36),
    price DECIMAL(10, 2), -- Use DECIMAL for price to handle floating-point precision
    qty INT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Set the date to current timestamp
    status VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE wishlist (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36),
    product_id VARCHAR(36),
    price DECIMAL(10, 2), -- Use DECIMAL for price to handle floating-point precision
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
