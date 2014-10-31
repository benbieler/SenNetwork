Sententiaregum
==============

[![Build Status](https://travis-ci.org/Ma27/SenNetwork.svg?branch=master)](https://travis-ci.org/Ma27/SenNetwork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ma27/SenNetwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ma27/SenNetwork/?branch=master)


1) About
--------

Sententiaregum is free social network based on an AngularJS SPA (Single Page Application). In the background runs a RESTful 
webservice based on Symfony 2.

### Develop Methodologies

This application is designed as an AngularJS SPA with a smart UI. The CRUD operations handles a RESTful API based on 
Symfony2.
The API is tested by the BDD approach with PHPSpec and Behat

### Features

   - An Angular SPA application with a RESTful webservice
   - Extended bootstrap layout
   - Login and Registration interface
   - BDD approach with PHPSpec and Behat test suites

### Current ToDo

   - ~~According to [First experiences with BDD](http://www.youtube.com/watch?v=TrlQ7oWsXnk) the test suites should be refactored:~~
      - ~~Create a project-wide suite with Behat, which is kernel and container aware~~
      - ~~Create an object-wide suite with PHPSPec to test the behavior of the services, controllers and maybe domain logic (if the domain logic is complex enough)~~
   - ~~Test registration controller action with PHPSpec~~
   - ~~Create registration route~~
   - Split this large SocialNetworkingBundle into smaller bundles *(in progress)*
     - move user stuff to Sententiaregum\Bundle\User
     - rename namespaces (Ma27\SocialNetworkingBundle -> Sententiaregum\Bundle\*
   - Finish Angular registration code
   - "Captcha" for the registration rest api
   - Implement "create admin account" command

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


4) LICENSE
----------

This software is published under GPL license

Check out the [LICENSE](https://github.com/Ma27/SenNetwork/blob/master/LICENSE) file in the document root


5) Run tests
------------

In order to run the behavior tests of this application, you have to install the dev dependencies during the composer 
install. This works like this:

    composer install -d api
    
Now you can run the PHPSpec and Behat test suite:

    cd api
    bin/phpspec run
    bin/behat
