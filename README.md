Sententiaregum
==============

[![Build Status](https://travis-ci.org/Ma27/SenNetwork.svg?branch=master)](https://travis-ci.org/Ma27/SenNetwork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ma27/SenNetwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ma27/SenNetwork/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cbf672e8-94ee-4817-b59b-0723dcbcce37/mini.png)](https://insight.sensiolabs.com/projects/cbf672e8-94ee-4817-b59b-0723dcbcce37)


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
   - pre-configured vagrant box (see more about it: [Wasted Vagrant box](https://github.com/Mayflower/wasted.git))

### Current ToDo

   - Implement microblogging
       - Build GUI
       - Simple post function:
           - create posts (with images)
           - comment posts
           - share posts
           - follow users to receive their posts
   - "Captcha" for the registration rest api
   - Logout

2) Install
----------

Clone repository

    git clone -b master https://github.com/Ma27/SenNetwork.git /path/to/webroot
    cd /path/to/webroot

The smartest way to deploy is using rake.
    
    rake

__Note__: this commands creates an test admin user with name "root" and password "sen-unsafe-password222" 
__Note__: if you use the vagrant, please use this command:

    rake vagrant

###Install all dependencies

Ths application has lots of dependencies like PHP5.5, node.js or SASS.
You can automate installing these on Ubuntu and Mac OS X.

    # ubuntu
    rake setup:ubuntu
    
    # mac
    rake setup:mac

###Here are all required commands listed

Please install the software by the following shell commands

Add sass

    gem install sass

Install bower and grunt

    npm install -g grunt-cli
    npm install -g bower

Load Bower any node.js dependencies

    bower install
    npm install

Build production environment

    grunt
    
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

### Shortcut

You could ran the install commands by executing the following command after cloning of this repository

    ./setup.sh

### Create admin user

    php app/console sententiaregum:user:create-admin --name={admin name} --password={password} --email={email}

3) Check requirements:
----------------------

Please check at first the Symfony 2 requirements

    php api/app/check.php


### Additional criteria

  - PHP 5.5.0 or higher
  - MySQL 5 database
  - WebSocket support
  - Bower (for frontend stuff)
  - node.js (used for grunt tasks)
  - SASS (style sheets are written in scss)
  - Composer (autoloader and package manager for symfony)


4) LICENSE
----------

This software is published under GPL license

Check out the [LICENSE](https://github.com/Ma27/SenNetwork/blob/master/LICENSE) file in the document root


5) Run tests
------------

In order to run the behavior tests of this application, you have to install the dev dependencies during the composer 
install. This works like this:

    composer install -d api
    
You can run all the tests quickliy with the following command:

    rake test
 
Now you can run the PHPSpec and Behat test suite:

    cd api
    bin/phpspec run
    bin/behat


6) Develop
----------

I'm using a vagrant box called wasted to develop this application: 
You can use this application local with the on-package vagrant box

    cd vagrant
    vagrant up

More information about [Wasted](https://github.com/Mayflower/wasted.git)


7) IDE
------

I recommend using [PHPStorm 8](https://www.jetbrains.com/phpstorm/) with the [Symfony2 Plugin](https://www.jetbrains.com/phpstorm/)
so you have full AngularJS and Symfony2 support and completion in your IDE.
