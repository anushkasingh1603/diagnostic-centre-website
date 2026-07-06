<?php
// Email notification placeholders for Phase 4.
// This file intentionally does NOT send emails yet.
// Integrate with PHPMailer or a provider (SendGrid/SMTP/etc.) later.

function emailPlaceholder_send(string $toEmail, string $subject, string $message): bool {
    // TODO: integrate real email sending.
    // Example:
    // - validate email
    // - use PHPMailer
    // - log success/failure

    // For now: do nothing, but keep the interface.
    return true;
}

function emailPlaceholder_queue(string $eventType, array $context = []): void {
    // TODO: enqueue email tasks (or write to a DB table / log file).
    // This is a no-op placeholder.
}

