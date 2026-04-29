#!/bin/bash
# This command runs the queue worker.
# It uses the default connection but listens to multiple queues.
# Ensure QUEUE_CONNECTION is set correctly in your environment (e.g., redis or database).
php artisan queue:work --queue=redis,default,invoice_notifications --verbose --tries=3 --timeout=90

