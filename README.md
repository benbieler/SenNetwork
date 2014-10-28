Sententiaregum
==============

[![Build Status](https://travis-ci.org/Ma27/SenNetwork.svg?branch=master)](https://travis-ci.org/Ma27/SenNetwork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ma27/SenNetwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ma27/SenNetwork/?branch=master)


1) About
--------

Sententiaregum is free social network based on an AngularJS SPA (Single Page Application). In the background runs a RESTful 
webservice based on Symfony 2.

### Features

   - An Angular SPA application with a RESTful webservice
   - Extended bootstrap layout
   - Login and Registration interface
   - Features tested with PHPSpec

### Current ToDo

   - Test registration controller action with PHPSpec
   - Create registration route
   - Finish Angular registration code

2) Install
----------

Please install the software by the following shell commands

Clone repository

    git clone -b master https://github.com/Ma27/SenNetwork.git /path/to/webroot
    cd /path/to/webroot

Load Bower dependencies

    npm install -g bower
    bower install
    
Note: If you don't have npm installed yet, you could install it by the following commands
    
    sudo apt-get update
    sudo apt-get install nodejs
    sudo apt-get install npm

Load Composer dependencies (without dev)

    curl -s http://getcomposer.org/installer | php
    composer install -d api --no-dev
    
Process doctrine migrations

    cd api
    php app/console doctrine:migrations:migrate --no-interaction
    
Flush redis cache

    php app/console redis:flush


3) Check requirements:
----------------------

Please check at first the Symfony 2 requirements

    php api/app/check.php


### Additional criteria

  - PHP 5.5.0 or higher
  - WebSocket support
  - Bower (for frontend stuff)
  
See also:

[Browsers supporting WebSockets](http://caniuse.com/#feat=websockets)

4) Run tests
------------

In order to run the behavior tests of this application, you have to install the dev dependencies during the composer 
install. This works like this:

    composer install -d api
    
Now you can run the PHPSpec test suite:

    cd api
    bin/phpspec run
