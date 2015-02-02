require 'rake'

# Travis CI tasks
namespace :travis do
    desc "This task prepares the travis ci for a new build"
    task :build do
        sh %{printf "\n" | pecl install imagick}
        sh "mysql -e 'CREATE DATABASE IF NOT EXISTS symfony;'"
        sh %{echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root}
        sh %{npm install -g bower}
        Rake::task['deploy:up'].execute[installDev => true]
    end

    desc "This task drops the build on travis ci"
    task :purge => ["deploy:purge"] do
        sh %{wget https://scrutinizer-ci.com/ocular.phar}
        sh %{php ocular.phar code-coverage:upload --format=php-clover coverage.clover}
    end
end

# Test tasks
namespace :test do
    desc "Executes the phpunit test suite"
    task :phpunit do
        sh "bin/phpunit -c app --coverage-clover=coverage.clover"
    end

    desc "Executes all testsuites"
    task :all => ['test:phpunit']
end

# Deploy tasks
namespace :deploy do
    desc "This task runs the deployment"
    task :up, [:installDev] do |installDev|
        if true == installDev
            composerArg = '--dev'
        else
            composerArg = '--no-dev'
        end

        sh "composer install #{composerArg}"
    end

    desc "This task purges the deployed application"
    task :purge do
        sh "php app/console doctrine:schema:drop"
    end

    desc "This task updates the application"
    task :modify do
        sh "git pull"
        sh "composer update"
    end
end
