-- Migration: 003_create_gallery_table.sql
-- Creates gallery table used by gallery.php and admin/gallery.php

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uploaded_by INT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (uploaded_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

-- Helpful indexes
CREATE INDEX IF NOT EXISTS idx_gallery_uploaded_by ON gallery(uploaded_by);
CREATE INDEX IF NOT EXISTS idx_gallery_created_at ON gallery(created_at);

