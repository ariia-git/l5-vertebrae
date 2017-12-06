#!/usr/bin/env bash
composer install -o --no-progress
php artisan clear-compiled
php artisan ide-helper:generate
php artisan ide-helper:meta
php artisan migrate
php artisan cache:clear
php artisan view:clear
yarn
npm run dev