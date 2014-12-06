desc "Task which installs all the frontend stuff"
task :installFrontend do
    puts "Installing node.js dependencies"
    sh %{npm install}
    puts "Installing Bower dependencies"
    sh %{bower install}
    puts "Running grunt tasks"
    sh %{grunt build-production}
end

# on my vagrant box "npm install" fails and this command has the fixes
desc "Task which installs all the frontend stuff on a vagrant box"
task :installFrontendForVagrant do
    puts "Installing node.js dependencies"
    sh %{sudo npm install --no-bin-links}
    puts "Installing Bower dependencies"
    sh %{bower install}
    puts "Running grunt tasks"
    sh %{grunt build-production}
end

desc "Task which installs the backend"
task :installBackend do
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

desc "Task which tests the features"
task :testBehat do
    puts "Processing behat tests"
    Dir.chdir('api') do
        sh %{bin/behat}
    end
end

desc "Task which tests the specs"
task :testPHPSpecs do
    puts "Processing specs"
    Dir.chdir('api') do
        sh %{bin/phpspec run --format=dot}
    end
end

desc "Custom tasks which prepares Travis CI for a test"
task :prepareTravisCI do
    puts "Run commands to prepare travis ci"
    sh %{printf "\n" | pecl install imagick}
    sh %{mysql -e "CREATE DATABASE IF NOT EXISTS symfony;" -u root}
    sh %{echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root}
    sh %{gem install sass}
    sh %{npm install -g bower}
    sh %{npm install -g grunt-cli}
end

desc "Task which installs the core dependencies on ubuntu"
task :fetchCoreOnUbuntu do
    sh %{sudo apt-get update}
    sh %{sudo apt-get install php5}
    sh %{sudo apt-get install nodejs}
    sh %{sudo apt-get install npm}
    sh %{sudo apt-get install rubygems-integration}
end

desc "Task which installs the core dependencies on OS X"
task :fetchCoreOnMacOSX do
    sh %{brew update}
    sh %{brew install php55}
    sh %{brew install node}
    puts "On brew ruby gems is already installed"
end

desc "Task which installs the core dependencies of this application"
task :fetchDependencies do
    sh %{npm install -g bower}
    sh %{npm install -g grunt-cli}
    sh %{gem install rake}
    sh %{gem install sass}
end

task :test => [:testPHPSpecs, :testBehat]
task :default => [:installFrontend, :installBackend]
task :travis => [:prepareTravisCI, :default]
task :vagrant => [:installFrontendOnVagrant, :installBackend]
