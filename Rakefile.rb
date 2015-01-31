desc "This task installs the application on that server"
task :deploy do
    sh %{composer install}
    sh %{bower install}
    sh %{php app/console doctrine:schema:create}
end

desc "This task tests the application"
task :test do
    sh %{bin/phpunit -c app}
end

desc "This task shows the dependencies of this application"
task :showDependencies do
    puts "PHP 5.5 or higher"
    puts "Composer"
    puts "Bower"
end

desc "This task purges if the software should be uninstalled"
task :uninstallData do
    sh %{php app/console doctrine:schema:drop}
end

desc "This task prepares the software for travis CI"
task :prepareCI do
    sh %{printf "\n" | pecl install imagick}
    sh %{mysql -e 'CREATE DATABASE IF NOT EXISTS symfony;'}
    sh %{echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root}
    sh %{npm install -g bower}
    sh %{rake showDependencies}
    sh %{rake deploy}
end
