Sententiaregum Refactoring
==========================

####Build:
[![Build Status](https://travis-ci.org/Ma27/SenNetwork.svg?branch=refactor)](https://travis-ci.org/Ma27/SenNetwork)

####Code Quality
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ma27/SenNetwork/badges/quality-score.png?b=refactor)](https://scrutinizer-ci.com/g/Ma27/SenNetwork/?branch=refactor)
[![SensioLabs Insight](https://insight.sensiolabs.com/projects/cbf672e8-94ee-4817-b59b-0723dcbcce37/mini.png)](https://insight.sensiolabs.com/projects/cbf672e8-94ee-4817-b59b-0723dcbcce37)
[![Codacy Badge](https://www.codacy.com/project/badge/c64875fe25804e8f80f85cfaf1a50fd1)](https://www.codacy.com/public/maximilianbosch27/SenNetwork)
[![Code Coverage](https://scrutinizer-ci.com/g/Ma27/SenNetwork/badges/coverage.png?b=refactor)](https://scrutinizer-ci.com/g/Ma27/SenNetwork/?branch=refactor)


1) What is sententiaregum
-------------------------
Sententiaregum is a project which combines AngularJS and Symfony2 in order to build a social network with a great UX.

The frontend contains an angular Single Page Application (=SPA) and is styled with Foundation 5.
The backend is built with Symfony2 and is a large REST API using FOSRest.

2) Contribute
-------------

You can install sententiaregum locally in order to develop.

Clone repository

    git clone -b refactor https://github.com/Ma27/SenNetwork.git /path/to/folder
    
Start VM and login over SSH

    cd vagrant
    vagrant up
    vagrant ssh

Configure application (inside SSH interface)

    cd /var/www/sententiaregum
    rake deploy:up[true]

Now you can start contributing!

Full test

    rake test:all

3) Deploy
---------

####Requirements
- PHP 5.5 or higher
- Bower
- MySQL 5.5
- Composer
- Imagick extension for PHP
- WebSockets

####Installation
Symfony requires some specific server configuration in order to work properly: [Symfony2 Server configuration](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html)

The deployment is similar to the local installation but it doesn't use the vagrant environment:

Install repository

    git clone -b refactor https://github.com/Ma27/SenNetwork.git /path/to/webroot

Execute rake tasks

    rake deploy:up[false]

Now is your installation deployed on the server and ready to use.

Update application

    rake deploy:modify
