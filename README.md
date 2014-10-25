Sententiaregum
==============

[![Build Status](https://travis-ci.org/Ma27/SenNetwork.svg?branch=master)](https://travis-ci.org/Ma27/SenNetwork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ma27/SenNetwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ma27/SenNetwork/?branch=master)

Sententiaregum is free social network based on an angularjs one-page application. In the background runs a RESTful 
webservice based on Symfony 2.


1) Install
----------

Please install the software by the following commands

Clone repository

    git clone -b master https://github.com/Ma27/Sententiaregum.git /path/to/webroot
    cd /path/to/webroot

Load Bower dependencies

    npm install -g bower
    bower install

Load Composer dependencies (without dev)

    curl -s http://getcomposer.org/installer | php
    composer update -d api --no-dev
    
Process doctrine migrations

    cd api
    php app/console doctrine:migrations:migrate --no-interaction
    
Flush redis cache

    php app/console redis:flush


2) Check requirements:
----------------------

Please check at first the Symfony 2 requirements

    php api/app/check.php


Now check out the following criterions:

  - PHP 5.5.0 or higher
  - WebSocket support
  - Bower (for frontend stuff)

3) Test application
-------------------

In order to run tests, you have to install the dev (require-dev section in composer.json) dependencies through composer.

Run behavior and unit tests:

    cd api
    bin/phpspec run
