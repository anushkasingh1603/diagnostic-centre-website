-- Migration 009: Create careers table

CREATE TABLE IF NOT EXISTS careers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    department VARCHAR(100) NOT NULL,
    description TEXT NULL,
    requirements TEXT NULL,
    location VARCHAR(150) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_careers_is_active_posted_at ON careers (is_active, posted_at, id);

