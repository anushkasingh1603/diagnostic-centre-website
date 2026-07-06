-- Migration: 005_create_additional_indexes_and_fk_safety.sql
-- Adds missing FK/index safety if users.role/table exists.

-- feedback indexes (idempotent)
CREATE INDEX IF NOT EXISTS idx_feedback_user_id ON feedback(user_id);
CREATE INDEX IF NOT EXISTS idx_feedback_created_at ON feedback(created_at);

-- gallery indexes (idempotent)
CREATE INDEX IF NOT EXISTS idx_gallery_uploaded_by ON gallery(uploaded_by);
CREATE INDEX IF NOT EXISTS idx_gallery_created_at ON gallery(created_at);

