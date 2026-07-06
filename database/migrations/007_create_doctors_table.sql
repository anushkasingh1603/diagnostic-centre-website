-- Migration 007: Create doctors table
-- Doctors are dynamic specialist cards shown on the homepage.

CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    specialization VARCHAR(150) NOT NULL,
    bio TEXT NULL,
    image_path VARCHAR(255) NULL,
    tags VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_doctors_is_active_sort_order ON doctors (is_active, sort_order, id);

