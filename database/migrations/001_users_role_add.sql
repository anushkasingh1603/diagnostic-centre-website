-- Migration: 001_users_role_add.sql
-- Adds users.role column and defaults existing users to 'user'.

ALTER TABLE users
    ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user';

-- Optional: for safety in case MySQL version requires table rebuild, keep.

