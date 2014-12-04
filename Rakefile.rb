task :installFrontend do
    puts "Installing node.js dependencies"
    `npm install`
    puts "Installing Bower dependencies"
    `bower install`
    puts "Running grunt tasks"
    `grunt build-production`
end

task :installBackend do
    Dir.chdir('api') do
        puts "Installing Composer packages and running symfony deploy tasks"
        `composer install`
        puts "Checking Symfony 2 requirements"
        `php app/check.php`
        puts "Creating Doctrine Schema"
        `php app/console doctrine:migrations:migrate --no-interaction`
        puts "Creating admin user"
        `php app/console sententiaregum:user:create-admin --name=root --password=sen-unsafe-password222 --email=root@example.org`
        puts "Flush redis"
        `php app/console redis:flush`
    end
end

task :testBehat do
    puts "Processing behat tests"
    Dir.chdir('api') do
        `bin/behat`
    end
end

task :testPHPSpecs do
    puts "Processing specs"
    Dir.chdir('api') do
        `bin/phpspec run`
    end
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
