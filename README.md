Installation Process

1. Install Composer
2. Create laravel project via the composer
    - composer create-project laravel/laravel example-app
    - In my case my project name is PRAXXYS
3. Install Breeze using this command
    - php artisan breeze:install
4. Install Vue using this command
    - php artisan breeze:install vue
5. Next in .env file 
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
6. Run this command (all neccessary migration were created and seeder)
    - php artisan migrate
7. Then run the command below to compile application's frontend assets
    - npm install
    - npm run dev
  
Note: Default password for default user is 'password' define in UserFactory class.
