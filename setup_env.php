<?php
if (!file_exists('.env')) {
    copy('.env.example', '.env');
    echo "Copied .env.example to .env successfully.\n";
} else {
    echo ".env file already exists.\n";
}

if (!file_exists('database/database.sqlite')) {
    touch('database/database.sqlite');
    echo "Created database/database.sqlite successfully.\n";
} else {
    echo "database/database.sqlite already exists.\n";
}
