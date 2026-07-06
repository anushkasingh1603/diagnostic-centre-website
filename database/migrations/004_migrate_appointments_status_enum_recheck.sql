-- Migration: 004_migrate_appointments_status_enum_recheck.sql
-- Safety/compat migration.
-- Confirms appointments.status is an ENUM with required values.
-- Note: This cannot ALTER ENUM values reliably without knowing existing definition.
-- Use manually if needed.

-- No-op placeholder with documentation.
-- Required values per PHP:
-- Pending, Confirmed, Completed, Cancelled

