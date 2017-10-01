#!/bin/bash

clear

composer install

php -d display_errors ./bin/phpunit --coverage-html=html

open html/index.html
