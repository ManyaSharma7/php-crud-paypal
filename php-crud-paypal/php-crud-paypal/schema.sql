-- Run this once to create the database and table
CREATE DATABASE IF NOT EXISTS crud_paypal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_paypal;

CREATE TABLE IF NOT EXISTS products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)   NOT NULL,
    description VARCHAR(500)   DEFAULT '',
    price       DECIMAL(10,2)  NOT NULL,
    stock       INT            NOT NULL DEFAULT 0,
    created_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO products (name, description, price, stock) VALUES
('Wireless Mouse', 'Ergonomic 2.4GHz wireless mouse', 12.99, 50),
('Mechanical Keyboard', 'RGB backlit mechanical keyboard', 45.50, 30),
('USB-C Hub', '7-in-1 USB-C hub adapter', 21.00, 75);
