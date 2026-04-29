#!/bin/bash
# This command runs the queue worker.
# We include both the 'redis' and 'default' queues, plus 'invoice_notifications'.
php artisan queue:work redis --queue=redis,default --verbose --tries=3 --timeout=90
