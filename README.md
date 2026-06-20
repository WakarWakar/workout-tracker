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

(Go to your program folder)
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

0. When you write new databases, "php artisan migrate"
-------------------

# MVP Spec

- Users can register, login, logout and log workouts (view, edit, delete). 
- They only see their own data.
- Create workout = date, list of exercises to choose from with reps and weight (optional), user can name their workout.
- Exercises are selected by the user from an existing list.

-------------------

# To Do

- Do not allow "create workout" button to work or submit if the data is not allowed (date in past, empty fields, letters for ints)
- Maybe don't force everything filled in for submissions of creating new workouts.
- When creating exercise definitions fail -> the UI coloring is in frontend html, need change later
- Create Many-To-Many relationship: One exercise hits multiple muscles. (currently only 1 "muscles_worked" per exercise -> not good)
- Check 1 exercise has 1 instance of relationship for every muscle_worked
- Learn what "pivot tables" are because your exercise_definition.list(worked_muscle) uses them.
- Add 403 redirect to "cannot find page" page.
- Write tests.
- User auto redirect to /dashboard
- Stay idiomatic
- Make data in objects nullable, that should allow you to delete items that are being used by other items

--------------------

# Security design choices

- Obfuscation of links by returning 404 page when trying to access unauthorized pages.
- Least privileges
- Role based access control