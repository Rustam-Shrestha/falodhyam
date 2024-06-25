-- Create the first database
CREATE DATABASE IF NOT EXISTS falodhyam_parties;
USE falodhyam_parties;

-- Create the 'buyers' table first because other tables depend on it
CREATE TABLE buyers (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(10) NOT NULL,
    address VARCHAR(255) NOT NULL,
    house_number VARCHAR(8) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create the 'seller' table next as 'products' table depends on it
CREATE TABLE seller (
    `s-id` INT(20) NOT NULL AUTO_INCREMENT,
    `s-name` VARCHAR(50) NOT NULL,
    `s-email` VARCHAR(50) NOT NULL,
    `s-password` VARCHAR(20) NOT NULL,
    `s-profile` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`s-id`)
);

-- Create the 'products' table next as 'cart', 'orders', and 'wishlist' tables depend on it
CREATE TABLE products (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2),
    image VARCHAR(255),
    product_detail TEXT,
    status varchar(20),
    `s-id` INT(20) NOT NULL,
    FOREIGN KEY (`s-id`) REFERENCES seller(`s-id`) ON DELETE CASCADE ON UPDATE CASCADE,
    type ENUM('Berries', 'Drupes', 'Pomes', 'Citrus Fruits', 'Melons', 'Dried Fruits', 'Tropical Fruits', 'Others')
);

-- Now create the dependent tables with ON DELETE CASCADE option
CREATE TABLE cart (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36),
    product_id VARCHAR(36),
    price DECIMAL(10, 2),
    qty INT,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES buyers(id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
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
    price DECIMAL(10, 2),
    qty INT,
    date_ordered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES buyers(id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE wishlist (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36),
    product_id VARCHAR(36),
    price DECIMAL(10, 2),
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES buyers(id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Now create the second database
CREATE DATABASE IF NOT EXISTS falodhyam_admin;
USE falodhyam_admin;

CREATE TABLE admin (
    id INT(20) NOT NULL AUTO_INCREMENT,
    useremail VARCHAR(50) NOT NULL,
    password VARCHAR(250) NOT NULL,
    PRIMARY KEY (id)
);

-- Insert admin example
INSERT INTO admin (id, useremail, password)
VALUES (1, 'fruitadmin097@gmail.com', 'fruit2097');

