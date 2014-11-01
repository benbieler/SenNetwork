#!/bin/sh

main() {
    echo "In order to run setup, please check out, that you have the following programms installed:" && \
    echo " - Composer" && \
    echo " - PHP executable" && \
    echo " - Bower" && \

    read -p "After checking out, please press enter to continue... "

    setup
}

setup() {
    echo "[setup] Bringing your composer version up to date" && \
        composer self-update
    echo "[setup] Installing composer dependencies (without dev)" && \
        composer install -d api --no-dev
    echo "[setup] Check Symfony2 requirements" && \
        php api/app/check.php
    echo "[setup] Install bower dependencies" && \
        bower install
    echo "[setup] Load doctrine migrations" && \
        cd api
        php app/console doctrine:migrations:migrate --no-interaction
}

main
