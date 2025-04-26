#!/bin/bash

# Wait for the migrate_and_test container to exit
docker wait laravel_migrate_and_test

# Stop the mysql-testing container
docker stop laravel_db_testing
