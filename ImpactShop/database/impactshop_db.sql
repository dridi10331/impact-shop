-- =====================================================
-- ImpactShop Database - Complete Schema (Fresh Start)
-- Date: 2025-12-05
-- =====================================================

-- Drop Database completely
DROP DATABASE IF EXISTS impactshop_db;

-- Create Database
CREATE DATABASE impactshop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE impactshop_db;

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;y_redemptions;
DROP TABLE IF EXISTS loyalty_rewards;
DROP TABLE IF EXISTS loyalty_points;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS shippings;
DROP TABLE IF EXISTS delivery_zones;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_status_history;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS customers;

-- =====================================================
-- CREATE ALL TABLES (without foreign keys first)
-- =====================================================

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_fr VARCHAR(255) NOT NULL,
    description_en TEXT,
    description_fr TEXT,
    parent_id INT DEFAULT NULL,
    img_name VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_parent (parent_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_fr VARCHAR(255) NOT NULL,
    description_en TEXT,
    description_fr TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    img_name VARCHAR(255),
    stock INT DEFAULT 0,
    category_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category_id),
    INDEX idx_price (price),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customers Table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'Tunisia',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    shipping_cost DECIMAL(10,2) DEFAULT 0.00,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    final_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('pending', 'paid', 'processing', 'shipped', 'delivered', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'paypal',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_customer (customer_id),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Status History Table
CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by VARCHAR(100),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments Table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method VARCHAR(50) NOT NULL DEFAULT 'paypal',
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255),
    paid_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order (order_id),
    INDEX idx_status (status),
    INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Delivery Zones Table
CREATE TABLE delivery_zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zone_name VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL,
    cities TEXT,
    base_cost DECIMAL(10,2) NOT NULL DEFAULT 7.00,
    extra_kg_cost DECIMAL(10,2) DEFAULT 1.00,
    estimated_days_min INT DEFAULT 1,
    estimated_days_max INT DEFAULT 3,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_region (region),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Shippings Table
CREATE TABLE shippings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    delivery_zone_id INT DEFAULT NULL,
    carrier VARCHAR(100) DEFAULT 'ImpactShop Express',
    tracking_number VARCHAR(100),
    status ENUM('processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'returned') DEFAULT 'processing',
    recipient_name VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'Tunisia',
    phone VARCHAR(50),
    shipping_cost DECIMAL(10,2) DEFAULT 0.00,
    weight DECIMAL(10,2) DEFAULT 0.00,
    estimated_delivery DATE,
    shipped_at DATETIME,
    delivered_at DATETIME,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order (order_id),
    INDEX idx_tracking (tracking_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews Table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating TINYINT NOT NULL,
    title VARCHAR(255),
    comment TEXT,
    is_verified TINYINT(1) DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product (product_id),
    INDEX idx_customer (customer_id),
    INDEX idx_rating (rating),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages Table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('general', 'order', 'product', 'complaint', 'suggestion') DEFAULT 'general',
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    is_read TINYINT(1) DEFAULT 0,
    is_replied TINYINT(1) DEFAULT 0,
    reply_message TEXT,
    replied_by VARCHAR(100),
    replied_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_read (is_read),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Loyalty Points Table
CREATE TABLE loyalty_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_id INT DEFAULT NULL,
    points INT NOT NULL,
    type ENUM('earned', 'bonus', 'redeemed', 'expired', 'adjustment') NOT NULL DEFAULT 'earned',
    description VARCHAR(255),
    expires_at DATE DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_customer (customer_id),
    INDEX idx_order (order_id),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Loyalty Rewards Table
CREATE TABLE loyalty_rewards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    points_required INT NOT NULL DEFAULT 0,
    reward_type ENUM('discount_percent', 'discount_fixed', 'free_shipping', 'free_product', 'bonus_points', 'exclusive_access') DEFAULT 'discount_percent',
    value DECIMAL(10,2) DEFAULT 0.00,
    icon VARCHAR(50) DEFAULT 'fa-gift',
    max_uses INT DEFAULT NULL,
    current_uses INT DEFAULT 0,
    valid_from DATE DEFAULT NULL,
    valid_until DATE DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_points (points_required),
    INDEX idx_active (is_active),
    INDEX idx_type (reward_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Loyalty Redemptions Table
CREATE TABLE loyalty_redemptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    reward_id INT NOT NULL,
    order_id INT DEFAULT NULL,
    points_used INT NOT NULL,
    code VARCHAR(50),
    status ENUM('active', 'used', 'expired', 'cancelled') DEFAULT 'active',
    used_at DATETIME,
    expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_customer (customer_id),
    INDEX idx_reward (reward_id),
    INDEX idx_code (code),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ADD ALL FOREIGN KEYS (after all tables exist)
-- =====================================================

ALTER TABLE categories ADD CONSTRAINT fk_category_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL;
ALTER TABLE products ADD CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;
ALTER TABLE orders ADD CONSTRAINT fk_order_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;
ALTER TABLE order_items ADD CONSTRAINT fk_orderitem_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
ALTER TABLE order_items ADD CONSTRAINT fk_orderitem_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;
ALTER TABLE order_status_history ADD CONSTRAINT fk_statushistory_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
ALTER TABLE payments ADD CONSTRAINT fk_payment_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
ALTER TABLE shippings ADD CONSTRAINT fk_shipping_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
ALTER TABLE shippings ADD CONSTRAINT fk_shipping_zone FOREIGN KEY (delivery_zone_id) REFERENCES delivery_zones(id) ON DELETE SET NULL;
ALTER TABLE reviews ADD CONSTRAINT fk_review_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;
ALTER TABLE reviews ADD CONSTRAINT fk_review_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;
ALTER TABLE messages ADD CONSTRAINT fk_message_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL;
ALTER TABLE loyalty_points ADD CONSTRAINT fk_loyaltypoints_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;
ALTER TABLE loyalty_points ADD CONSTRAINT fk_loyaltypoints_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL;
ALTER TABLE loyalty_redemptions ADD CONSTRAINT fk_redemption_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE;
ALTER TABLE loyalty_redemptions ADD CONSTRAINT fk_redemption_reward FOREIGN KEY (reward_id) REFERENCES loyalty_rewards(id) ON DELETE CASCADE;
ALTER TABLE loyalty_redemptions ADD CONSTRAINT fk_redemption_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;


-- =====================================================
-- SAMPLE DATA
-- =====================================================

-- Categories
INSERT INTO categories (name_en, name_fr, description_en, description_fr, is_active) VALUES
('Food & Water', 'Nourriture & Eau', 'Food and water supplies for emergencies', 'Fournitures alimentaires et eau pour les urgences', 1),
('Medical Supplies', 'Fournitures Medicales', 'Medical and health supplies', 'Fournitures medicales et de sante', 1),
('Shelter', 'Abri', 'Shelter and housing supplies', 'Fournitures d''abri et de logement', 1),
('Clothing', 'Vetements', 'Clothing and textiles', 'Vetements et textiles', 1),
('Tools & Equipment', 'Outils & Equipement', 'Essential tools and equipment', 'Outils et equipements essentiels', 1);

-- Products
INSERT INTO products (name_en, name_fr, description_en, description_fr, price, img_name, stock, category_id, is_active) VALUES
('Water Filter', 'Filtre a Eau', 'Portable water filter for clean drinking water', 'Filtre a eau portable pour eau potable', 25.00, 'water_filter.jpeg', 50, 1, 1),
('Emergency Food Kit', 'Kit Alimentaire d''Urgence', 'Emergency food supplies for one week', 'Fournitures alimentaires d''urgence pour une semaine', 45.00, 'image1.jpg', 30, 1, 1),
('First Aid Kit', 'Trousse de Premiers Soins', 'Complete first aid kit', 'Trousse de premiers soins complete', 35.00, 'image2.jpg', 40, 2, 1),
('Emergency Blanket', 'Couverture d''Urgence', 'Thermal emergency blanket', 'Couverture thermique d''urgence', 15.00, 'image3.jpg', 100, 3, 1),
('Warm Jacket', 'Veste Chaude', 'Warm winter jacket', 'Veste chaude d''hiver', 55.00, 'image4.jpeg', 25, 4, 1),
('Survival Tent', 'Tente de Survie', 'Compact 2-person survival tent', 'Tente de survie compacte pour 2 personnes', 75.00, 'image6.jpg', 20, 3, 1),
('Medical Mask Box', 'Boite de Masques Medicaux', 'Box of 50 medical grade masks', 'Boite de 50 masques de qualite medicale', 12.00, 'image7.jpeg', 200, 2, 1),
('Flashlight Kit', 'Kit Lampe de Poche', 'LED flashlight with extra batteries', 'Lampe de poche LED avec piles supplementaires', 18.00, 'image8.jpeg', 60, 5, 1);

-- Customers
INSERT INTO customers (first_name, last_name, email, phone, address, city, postal_code, country) VALUES
('Ahmed', 'Ben Ali', 'ahmed.benali@email.com', '+216 20 123 456', '15 Rue de la Liberte', 'Tunis', '1000', 'Tunisia'),
('Fatma', 'Trabelsi', 'fatma.trabelsi@email.com', '+216 25 789 012', '28 Avenue Habib Bourguiba', 'Sfax', '3000', 'Tunisia'),
('Mohamed', 'Gharbi', 'mohamed.gharbi@email.com', '+216 22 456 789', '5 Rue Ibn Khaldoun', 'Sousse', '4000', 'Tunisia');

-- Delivery Zones (Tunisia)
INSERT INTO delivery_zones (zone_name, region, cities, base_cost, extra_kg_cost, estimated_days_min, estimated_days_max, is_active) VALUES
('Grand Tunis', 'Tunis', 'Tunis, Ariana, Ben Arous, Manouba', 7.00, 1.00, 1, 2, 1),
('Nord-Est', 'Nord-Est', 'Bizerte, Nabeul, Zaghouan', 9.00, 1.50, 2, 3, 1),
('Nord-Ouest', 'Nord-Ouest', 'Beja, Jendouba, Le Kef, Siliana', 11.00, 1.50, 2, 4, 1),
('Centre-Est', 'Centre-Est', 'Sousse, Monastir, Mahdia, Sfax', 10.00, 1.50, 2, 3, 1),
('Centre-Ouest', 'Centre-Ouest', 'Kairouan, Kasserine, Sidi Bouzid', 12.00, 2.00, 3, 5, 1),
('Sud-Est', 'Sud-Est', 'Gabes, Medenine, Tataouine', 14.00, 2.00, 3, 5, 1),
('Sud-Ouest', 'Sud-Ouest', 'Gafsa, Tozeur, Kebili', 15.00, 2.50, 4, 6, 1);

-- Loyalty Rewards
INSERT INTO loyalty_rewards (name, description, points_required, reward_type, value, icon, is_active) VALUES
('5% de reduction', 'Obtenez 5% de reduction sur votre prochaine commande', 100, 'discount_percent', 5.00, 'fa-percent', 1),
('10% de reduction', 'Obtenez 10% de reduction sur votre prochaine commande', 250, 'discount_percent', 10.00, 'fa-percent', 1),
('15% de reduction', 'Obtenez 15% de reduction sur votre prochaine commande', 400, 'discount_percent', 15.00, 'fa-percent', 1),
('Livraison gratuite', 'Livraison gratuite sur votre prochaine commande', 150, 'free_shipping', 0.00, 'fa-truck', 1),
('5 TND de reduction', 'Obtenez 5 TND de reduction sur votre prochaine commande', 200, 'discount_fixed', 5.00, 'fa-tag', 1),
('10 TND de reduction', 'Obtenez 10 TND de reduction sur votre prochaine commande', 350, 'discount_fixed', 10.00, 'fa-tag', 1),
('50 Points Bonus', 'Recevez 50 points bonus', 300, 'bonus_points', 50.00, 'fa-star', 1),
('Acces VIP', 'Acces exclusif aux ventes privees', 500, 'exclusive_access', 0.00, 'fa-crown', 1);

-- Sample Orders
INSERT INTO orders (customer_id, total_amount, shipping_cost, discount_amount, final_amount, status, payment_method) VALUES
(1, 70.00, 7.00, 0.00, 77.00, 'completed', 'paypal'),
(2, 45.00, 10.00, 0.00, 55.00, 'shipped', 'paypal'),
(3, 90.00, 9.00, 5.00, 94.00, 'processing', 'card');

-- Order Items
INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES
(1, 1, 1, 25.00, 25.00),
(1, 3, 1, 35.00, 35.00),
(1, 4, 1, 15.00, 15.00),
(2, 2, 1, 45.00, 45.00),
(3, 5, 1, 55.00, 55.00),
(3, 3, 1, 35.00, 35.00);

-- Shippings
INSERT INTO shippings (order_id, delivery_zone_id, tracking_number, status, recipient_name, address, city, postal_code, shipping_cost, estimated_delivery) VALUES
(1, 1, 'IMP-TN-20251205-001', 'delivered', 'Ahmed Ben Ali', '15 Rue de la Liberte', 'Tunis', '1000', 7.00, '2025-12-07'),
(2, 4, 'IMP-TN-20251205-002', 'in_transit', 'Fatma Trabelsi', '28 Avenue Habib Bourguiba', 'Sfax', '3000', 10.00, '2025-12-08'),
(3, 2, 'IMP-TN-20251205-003', 'processing', 'Mohamed Gharbi', '5 Rue Ibn Khaldoun', 'Sousse', '4000', 9.00, '2025-12-09');

-- Loyalty Points
INSERT INTO loyalty_points (customer_id, order_id, points, type, description) VALUES
(1, 1, 77, 'earned', 'Points gagnes pour la commande #1'),
(2, 2, 55, 'earned', 'Points gagnes pour la commande #2'),
(3, 3, 94, 'earned', 'Points gagnes pour la commande #3'),
(1, NULL, 50, 'bonus', 'Bonus de bienvenue');

-- =====================================================
-- END OF SCHEMA
-- =====================================================
