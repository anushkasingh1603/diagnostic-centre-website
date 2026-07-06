-- Migration 006: Create notices table
-- Notices are dynamic announcements shown on the homepage.

CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    display_from DATE NULL,
    display_to DATE NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Helpful index for homepage lookups
CREATE INDEX IF NOT EXISTS idx_notices_is_active_sort_order ON notices (is_active, sort_order, id);

