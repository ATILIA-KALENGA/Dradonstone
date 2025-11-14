-- ===================================================
-- FORCE RESET SCRIPT FOR DRAGONSTONE DATABASE
-- Works even if foreign keys cause trouble
-- ===================================================

DROP DATABASE IF EXISTS dragonstonedb;
CREATE DATABASE dragonstonedb;
USE dragonstonedb;

-- ---------------------------------------------------
-- Create Tables
-- ---------------------------------------------------

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin','customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ---------------------------------------------------
-- Insert Sample Data
-- ---------------------------------------------------

INSERT INTO categories (name, description, image) VALUES
('Electronics', 'Phones, laptops, and gadgets', 'images/categories/electronics.jpg'),
('Clothing', 'Men and Women fashion items', 'images/categories/clothing.jpg'),
('Home & Kitchen', 'Appliances and kitchen accessories', 'images/categories/home_kitchen.jpg'),
('Books', 'All kinds of books and stationery', 'images/categories/books.jpg');

INSERT INTO products (category_id, name, description, price, image, stock) VALUES
(1, 'iPhone 14 Pro', 'Latest Apple smartphone with advanced camera system', 1199.99, 'images/products/iphone14.jpg', 10),
(1, 'Laptop Dell XPS 13', 'Lightweight ultrabook with Intel i7 processor', 999.99, 'images/products/dellxps13.jpg', 8),
(2, 'Men T-Shirt', 'Cotton round neck t-shirt available in various sizes', 19.99, 'images/products/tshirt.jpg', 25),
(3, 'Microwave Oven', 'Samsung 800W digital microwave with grill function', 89.99, 'images/products/microwave.jpg', 5),
(4, 'Atomic Habits', 'Bestseller book by James Clear about habit formation', 14.50, 'images/products/atomic_habits.jpg', 20);

INSERT INTO users (full_name, email, password, role) VALUES
('Admin User', 'admin@dragonstone.com', 'admin123', 'admin'),
('John Doe', 'john@dragonstone.com', 'john123', 'customer');

INSERT INTO orders (user_id, total, status) VALUES
(2, 1234.49, 'pending');

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 1199.99),
(1, 3, 2, 19.99);

-- ===================================================
-- END
-- ===================================================
