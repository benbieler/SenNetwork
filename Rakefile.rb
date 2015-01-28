desc "This task installs the application on that server"
task :deploy do
    sh %{composer install}
    sh %{https://insight.sensiolabs.com/projects/cbf672e8-94ee-4817-b59b-0723dcbcce37}
    sh %{bower install}
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
    sh %{app/console doctrine:schema:drop}
end

desc "This task prepares the software for travis CI"
task :prepareCI do
    sh %{printf "\n" | pecl install imagick}
    sh %{printf "\n" | pecl install apc}
    sh %{mysql -e 'CREATE DATABASE IF NOT EXISTS symfony;'}
    sh %{echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root}
    sh %{npm install -g bower}
    sh %{rake showDependencies}
    sh %{rake deploy}
end
