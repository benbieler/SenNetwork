task :installFrontend do
    puts "Installing node.js dependencies"
    `npm install`
    puts "Installing Bower dependencies"
    `bower install`
    puts "Running grunt tasks"
    `grunt build-production`
end

task :installBackend do
    `cd api`
    puts "Installing Composer packages and running symfony deploy tasks"
    `composer install`
    puts "Checking Symfony 2 requirements"
    `php app/check.php`
    puts "Creating Doctrine Schema"
    `php app/console doctrine:migrations:migrate --no-interaction`
    puts "Creating admin user"
    `php app/console sententiaregum:user:create-admin --name=root --password=sen-unsage-password --email=root@example.org`
    puts "Flush redis"
    `php app/console redis:flush`
    `cd ..`
end

task :testBehat do
    puts "Processing behat tests"
    `cd api`
    `bin/behat`
    `cd ..`
end

task :testPHPSpecs do
    puts "Processing specs"
    `cd api`
    `bin/phpspec run`
    `cd ..`
end

task :prepareTravisCI do
    puts "Run commands to prepare travis ci"
    `printf "\n" | pecl install imagick`
    `mysql -e "CREATE DATABASE IF NOT EXISTS symfony;" -u root`
    `echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root`
    `gem install sass`
    `npm install -g bower`
    `npm install -g grunt-cli`
end

task :test => [:testPHPSpecs, :testBehat]
task :default => [:installFrontend, :installBackend]
task :travis => [:prepareTravisCI, :default]
