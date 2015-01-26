desc "This task installs the application on that server"
task :deploy do
    sh %{composer install}
    sh %{bower install}
end

desc "This task tests the application"
task :test do
    sh %{bin/phpunit -c app}
end

desc "This task shows the dependencies of this application"
task :show-dependencies do
    puts "PHP 5.5 or higher"
    puts "Composer"
    puts "Bower"
end
