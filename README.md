# How to run Laravel tests :

Copy and rename `.env.example` to `.env` or `.env.local`

Generate an APP_KEY in this .env file :   php artisan key:generate 

Create a new DB and name it 'project' in your database manager (xamp, etc...)

Run the migrations `php artisan migrate`.

Serve the app on localhost:8000 `php artisan serve` // not mandatory to run the tests

Run a single Test  `php artisan test --filter test_task_with_no_user`

Run all Tests `php artisan test`

Note: BE CAREFULL !! the `use RefreshDatabase` instruction in `tests/Feature/RelationshipsTest.php` will empty the DB and run all migrations again before running each test.

### Use Database SQLite (in memory or on disk)

Add a connection in `config/database.php`
```
'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
```
And connect it in `phpunit.xml`
```
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

---
# Test Laravel Eloquent Relationships

There are PHPUnit tests in `tests/Feature/RelationshipsTest.php` file.

### Solutions are on bottom of this page

---

## Task 1. HasMany Defined Incorrectly.  

In `app/Models/User.php` file, the relationship is missing some parameter. Fix this.

Test method `test_user_create_task()`.

---

## Task 2. BelongsTo with Empty Relationship.

In the route `/tasks`, the table is loading with error, if it can't find the user related to the task. Fix this: the table should load, still listing all the tasks, just showing an empty space where the user name should have been.

There are multiple ways how to fix this, choose whichever way works for you.

Test method `test_task_with_no_user()`.

---

## Task 3. Two-level Relationship.

In the route `/users/{user}`, the table should load the comments that are written on the task that belong to a user. Define the relationship from User to Comment in the User model, so that the Blade file users/show.blade.php would work.

Test method `test_show_users_comments()`.

---

## Task 4. BelongsToMany - Pivot Table Name.

In the route `/roles`, the table should load the roles with the number of users belonging to them. But the relationship in `app/Models/Role.php` model is defined incorrectly, fix that relationship definition.

Test method `test_show_roles_with_users()`.

---

## Task 5. BelongsToMany - Extra Fields in Pivot Table.

In the route `/teams`, the table should show the teams with users, each user with a few additional fields. Fix the relationship definition in `app/Models/Team.php` so that the Blade file `teams/index.blade.php` would show the correct data.

Test method `test_teams_with_users()`.

---

## Task 6. HasMany - Average from Field Value

In the route `/countries`, the table should show the countries with average team size. Fix the Controller to load the relationship number, as it is expected in the Blade.

Test method `test_countries_with_team_size()`.

---

## Task 7. Polymorphic Attachments

In the route `/attachments`, the table should show the filenames and the class names of Task and Comment models. Fix the `app/Models/Attachment.php` relationship to make it work.

Test method `test_attachments_polymorphic()`.

---

## Task 8. Add BelongsToMany Row

In the POST route `/projects`, the project should be saved for a logged-in user, with start_date field from $request. Write that sentence in the Controller.

Test method `test_belongstomany_add()`.

---

## Task 9. Filter BelongsToMany Rows

In the route `/users`, the list should show only the users with at least one project. Fix the Controller to add this filter.

Test method `test_filter_users()`.

---

### You may need to serve your App with a populated MYSQL database for dev purposes 
### and at the same time use SQLite for the PHPUnit tests
---

Create an .env.testing file, set `APP_ENV` to `testing` and remove all `DB_` entries

Make sure your phpunit.xml has the following lines

<env name="DB_CONNECTION" value="memory_testing"/>
<env name="DB_DATABASE" value=":memory:"/>

Add the following array to your connections in database.php:

'connections' => [

   'memory_testing' => [
     'driver' => 'sqlite',
     'database' => ':memory:',
     'prefix' => '',
   ],

   ...
Finally, run 
	php artisan optimize:clear
 to clear the caches.

Your unit and feature tests should now be using the in-memory SQLite database, 
while your local should continue using the database configured in .env file.



APP KEY:
--------

php artisan key:generate --env=testing



MySQL error key too long:
------------------------

Solution 1:

In file appServiceProvider.php in function boot() ->   Schema::defaultStringLength(191);

Solution 2:

Inside config/database.php, replace this line for mysql

'engine' => null',

with

'engine' => 'InnoDB ROW_FORMAT=DYNAMIC',


Then retry    php artisan migrate:fresh


## Answer

Task 1

Add Task's foreign key 'users_id' in User model : $this->hasMany(Task::class, 'users_id');
otherwise Eloquent will use it's default foreign key 'user_id' (without 's')
