desc "This task tests the application"
task :test do
    sh %{bin/phpunit -c app --coverage-clover=coverage.clover}
end

desc "This task purges if the software should be uninstalled"
task :uninstall do
    sh %{php app/console doctrine:schema:drop --force}
end

desc "This task prepares the software for travis CI"
task :prepareCI do
    sh %{printf "\n" | pecl install imagick}
    sh %{mysql -e 'CREATE DATABASE IF NOT EXISTS symfony;'}
    sh %{echo "use mysql;\nUPDATE user SET password=PASSWORD('root') WHERE user = 'root';\nFLUSH PRIVILEGES;\n" | mysql -u root}
    sh %{npm install -g bower}
    sh %{composer install}
end

task :uninstallCI => "uninstall" do
    sh %{wget https://scrutinizer-ci.com/ocular.phar}
    sh %{php ocular.phar code-coverage:upload --format=php-clover coverage.clover}
end
