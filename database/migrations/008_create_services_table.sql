-- Migration 008: Create services table

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    short_description VARCHAR(255) NOT NULL,
    full_description TEXT NULL,
    prep_instructions TEXT NULL,
    price DECIMAL(10,2) NULL,
    image_path VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_services_is_active_sort_order ON services (is_active, sort_order, id);

