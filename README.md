# workout-tracker
Laravel framework try

-------------------
Requirements:
1. Install: PHP, Composer, MySQL Server
2. Add to PATH: 
- C:\Program Files\php
- C:\ProgramData\ComposerSetup\bin
- C:\Program Files\MySQL\MySQL Server 8.0\bin

-------------------
Installation steps:

1. composer install
2. copy .env.example .env (in powershell)
3. change contents .env file:
  "
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=root
  DB_PASSWORD=root
  " 
4. log into mysql: mysql -u root -p (password = root)
5. CREATE DATABASE your_database_name;
6. exit;

In php.ini file (path is C:/User/Program Files/php/php.ini) , uncomment -> extension=pdo_mysql

7. php artisan key:generate
8. php artisan session:table (might say migration already exists)
9. php artisan migrate
10. php artisan serve
