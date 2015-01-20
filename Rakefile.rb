desc "This task installs the application on that server"
task :deploy do
    sh %{composer install}
end

desc "This task tests the application"
task :test do
    sh %{bin/phpunit -c app}
end
