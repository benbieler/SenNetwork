# Main tasks
task :test => ["test:phpspec", "test:behat", "test:phpunit"]
task :default => ["install:backend", "install:npm", "install:frontend", :test]
task :travis => ["setup:travis", "setup:general", :default]
task :vagrant => ["install:npmVagrant", "install:frontend", "install:backend"]
task :mac => ["setup:mac", "setup:general"]
task :ubuntu => ["setup:ubuntu", "setup:general"]

# Task partials
namespace :install do
    desc "Task which installs the backend"
    task :backend do
        Dir.chdir('api') do
            puts "Installing Composer packages and running symfony deploy tasks"
            sh %{composer install}
            puts "Checking Symfony 2 requirements"
            sh %{php app/check.php}
            puts "Creating Doctrine Schema"
            sh %{php app/console doctrine:migrations:migrate --no-interaction}
            puts "Creating admin user"
            sh %{php app/console sententiaregum:user:create-admin --name=root --password=sen-unsafe-password222 --email=root@example.org}
            puts "Flush redis"
            sh %{php app/console redis:flushdb --no-interaction}
        end
    end

    desc "Installs npm dependencies on vagrant"
    task :npmVagrant do
        sh %{sudo npm install --no-bin-links}
    end

    desc "Installs npm dependencies on any machine"
    task :npm do
        sh %{npm install}
    end

    desc "Task which installs the frontend"
    task :frontend do
        puts "Installing Bower dependencies"
        sh %{bower install}
        puts "Running grunt tasks"
        sh %{grunt build-production}
    end
end

namespace :setup do
    desc "Custom mac tasks"
    task :mac do
        sh %{brew update}
        sh %{brew install php55}
        sh %{brew install node}
    end

    desc "Custom ubuntu tasks"
    task :ubuntu do
        sh %{sudo apt-get update}
        sh %{sudo apt-get install php5}
        sh %{sudo apt-get install nodejs}
        sh %{sudo apt-get install npm}
    end

    desc "Custom tasks which prepares Travis CI for a test"
    task :travis do
        puts "Run commands to prepare travis ci"
        sh %{printf "\n" | pecl install imagick}
        sh %{mysql -e "CREATE DATABASE IF NOT EXISTS symfony;" -u root}
        sh %{echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root}
    end

    desc "General setup tasks"
    task :general do
        sh %{npm install -g bower}
        sh %{npm install -g grunt-cli}
        sh %{gem install sass}
    end
end

namespace :test do
    desc "Task which tests the features"
    task :behat do
        puts "Processing behat tests"
        Dir.chdir('api') do
            sh %{bin/behat}
        end
    end

    desc "Task which tests the specs"
    task :phpspec do
        puts "Processing specs"
        Dir.chdir('api') do
            sh %{bin/phpspec run --format=dot}
        end
    end

    desc "Task which executes the phpunit test suites"
    task :phpunit do
        Dir.chdir('api') do
            sh %{bin/phpunit -c app}
        end
    end
end
