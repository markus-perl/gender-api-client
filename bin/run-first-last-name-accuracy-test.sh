#!/bin/bash

# Load environment variables from .env
if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

# Run the accuracy test script for first name and last name
php tests-integration/run-test-first-last-name.php tests-integration/accuracy-check.csv "$GENDER_API_KEY" "$GENDER_API_URL"
