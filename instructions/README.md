# Lab 3A - PHP Part 1

## Overview

This lab will give you the opportunity to add in some authentication using PHP and allow users to register and log in to your site. In lab 3B we will add the application logic. This lab builds off of the previous labs (as do all of the labs), so make sure you organize your code well so you will be able to read and understand it again later. A MySQL database will be used to store all of the user authentication information.

Our overall setup will be a LAMP stack: a **L**inux machine running an **A**pache server and a **M**ySQL database, serving **P**HP files.

To clarify, your app does not have to be actually functional as a task list by the end of lab 3A. You'll need to be able to log in and out, and register new users. You should be able to hide some content from anyone who isn't logged in, and show it to anyone who *is* logged in. In the next lab (Lab 3B), we'll transpile the JavaScript you wrote in the previous labs into PHP.

## Functionality

- Create a database to store user data
- Implement user authentication for your site
- Hash incoming passwords

## Concepts

- Simple Authentication (username and password)
- Containers
- Separation of Responsibility

## Technologies

- UML
- Docker
- PHP
- MySQL

## Resources

- [Official PHP Documentation](https://www.php.net/)
- [PHP MySQL Introduction](https://www.tutorialrepublic.com/php-tutorial/php-mysql-introduction.php)
- [PHP Sessions](https://www.w3schools.com/php/php_sessions.asp)
- [Prepared Statements](https://www.tutorialrepublic.com/php-tutorial/php-mysql-prepared-statements.php)

## Setup

### Development Environment (Personal Computer or Lab Computer)

1. Clone this repo onto your development computer, and open it in VSCode

2. Copy the `.env.example` template as a file called `.env`. Edit the `.env` with your own MySQL username, password, and database

3. Bring up your docker containers

4. Your development server should be running on `http://localhost`, and the development phpMyAdmin should be running on `http://localhost:8080`

5. Set up your database:

    - Log in to **phpMyAdmin** ([`http://localhost:8080`](http://localhost:8080))
    - Create a new database called `'lab_3'` (or whatever you named it in your `.env`)
    - Create a new table called `'user'` in the `'lab_3'` database
    - Add the following fields:

        | Name        | Type      | Length/Values  | Default         | Index   | A_I |  ...  |
        | ----------- | --------- | -------------- | --------------- | ------- | --- | ----- |
        | `id`        | `INT`     |                | ...             | Primary |  ☒  |  ...  |
        | `username`  | `VARCHAR` | `100`          | ...             | Unique  |  ☐  |  ...  |
        | `password`  | `VARCHAR` | `255`          | ...             | ...     |  ☐  |  ...  |
        | `logged_in` | `BOOLEAN` |                | `As defined: 0` | ...     |  ☐  |  ...  |

        > Note: Selecting the Auto Increment checkbox (`A_I`) will result in a dialogue box asking if you want it to be your primary key. Just click <kbd>Go</kbd>.

6. Navigate to [http://localhost/actions/login_action.php](http://localhost/actions/login_action.php) to see if your server connected properly to your database.

> 
: When you run `docker-compose down --volumes`, it deletes this database entirely. If you run `docker-compose down` *without* the flag, it won't delete your database. If you do run it with the flag, you'll have to create the `user` table in the database again.

### Production Environment (AWS Live Server)

1. SSH into your AWS EC2 instance environment

1. You should already have **Apache** installed from Lab 1. If not, go back and do it.

1. Install the rest of the LAMP stack (**PHP**, **MariaDB**, and **phpMyAdmin**) using the following command:
    ```sh

    sudo apt update && sudo apt install -y php php-mbstring php-zip php-gd php-json php-curl mariadb-server phpmyadmin

    ```
    As you do this, phpMyAdmin will prompt you for a few things:
    
    1. Which database:
        > Please choose the web server that should be automatically configured to run phpMyAdmin.
        >
        > Web server to reconfigure automatically:
        >
        > [ ] apache2<br>
        > [ ] lighttpd
        >
        > &lt;Ok>
        
        WARNING: When the prompt appears, “apache2” is highlighted, but not selected. If you do not hit <kbd>SPACE</kbd> to select Apache, the installer will not move the necessary files during installation. Press <kbd>Space</kbd> to select apache2 (it should look like `[*] apache2`), then <kbd>Enter</kbd>.
    1. Use dbconfig-common:
        > The phpmyadmin package must have a database installed and configured before it can be used. This can be optionally handled with dbconfig-common.
        >
        > If you are an advanced database administrator and know that you want to perform this configuration manually, or if your database has already been installed and configured, you should refuse this option. Details on what needs to be done should most likely be provided in /usr/share/doc/phpmyadmin.
        >
        > Otherwise, you should probably choose this option.
        >
        > Configure database for phpmyadmin with dbconfig-common?
        >
        > &lt;Yes> &lt;No>
        
        Press <kbd>Enter</kbd> to select `<yes>`.
    1. Password for phpMyAdmin:
        > Please provide a password for phpmyadmin to register with the database server. If left blank, a random password will be generated.
        >
        > MySQL application password for phpmyadmin:
        >
        > _____
        >
        > &lt;Ok> &lt;Cancel>
        
        Type in a password, and press <kbd>Enter</kbd>.
        
        > Password confirmation:
        >
        > _____
        >
        > &lt;Ok> &lt;Cancel>
    
        Type in the password again, and press <kbd>Enter</kbd>.

1. Set up your database:

    - Before you can log into **phpMyAdmin** you will need to create a root user with a password.

        - On your live server run the command `sudo mariadb`
        - Run the query statement: `SET PASSWORD FOR 'root'@'localhost' = PASSWORD('<yourPassword>');` Making sure to replace `<yourPassword>` with a password that you will **NEVER FORGET!** (when entering `<yourPassword>` remove angle brackets but keep the quotes). 
        - Then run `flush privileges;`
        - After that runs type `exit`
        - You should now be able to go to **phpMyAdmin** in your browser and login with user: `root` and the password you made
    - Log in to **phpMyAdmin** (`http://<aws-ip-address>/phpmyadmin`)
        > If a 404 not found error appears when you go to `http://<aws-ip-address>/phpmyadmin` there is a tip below that will help  
    - Create a new database with the same name as the one on your dev server
    - Create a users table with the same name as the one on your dev server
    - Add the same fields as the one on your dev server, so that it behaves exactly the same way

2. Create a new MySQL User with limited privileges to be used in your code:
    - The new user should have the same username and password as what's in the `docker-compose.yml` file, under `MYSQL_USER` and `MYSQL_PASSWORD`
    - Make sure this user (and the root user) is password protected
    - Enable `SELECT`, `INSERT`, `UPDATE`, and `DELETE` in the "Data" section, and only the `CREATE` checkbox in the "Structure" section
    
    > Note: Generally, there are two kinds of MySQL users: the root user with full privileges (there's only one), and the application users with limited privileges (with one per application). This is so that you have complete access to create and modify and drop MySQL users, databases, tables, records, etc. as root, but the application only has privileges to make, modify, and delete records pertaining to that application. If there's some vulnerability in your site and a malicious actor is able to execute arbitrary queries to your database, the worst they can do is remove the records pertaining to that application, but the rest of the database is still secure.
    >
    > There's a big difference between a MySQL user (like the one we just created, or the root user) and a site user (like one that will be inserted into your database). Do not get them confused. We only have two MySQL users, and one is root. The many site users are stored in the `lab_3.user` table, and there can be arbitrarily many of them. Any time a new person comes to your site and registers, another site user is made. Ask a TA if you need help with this distinction. 

3. Navigate to the `/var/www` folder and clone your repo here as well. Change your symbolic link to point to this new directory, and then `cd` into it.
   
4. In the your root folder (the same directory as your `.env.exmaple`), create a file called `.env` and paste the credentials into this file as before, notice the servername changes:

    ```bash
    MYSQL_SERVERNAME=localhost
    MYSQL_USER=youruser
    MYSQL_PASSWORD=yourpassword
    MYSQL_DATABASE=yourdatabase
    ```
5. SOURCE THIS FILE WHEN YOU'RE FINISHED. Read and use the `apache2_env_setup.sh` file to do this. 

    > Note: you didn't need to source the file in your development environment because docker-compose automatically uses any `.env` files present, and even passes them in as environment variables to our container. It's very important to source this file on the live server because Apache doesn't automatically load it.

6. Restart the Apache service:

    ```
    sudo service apache2 restart
    ```

You should now have a barebones site on both your dev and live servers.

## Instructions

### Step 1: Grab your old HTML

1. This is as easy as pasting the HTML from your old project into `index.php`

    > Note: PHP is technically HTML with some bonus syntax that allows you to do some scripting logic. Valid HTML is also valid PHP.

### Step 2: Authentication

1. In your project, there is a folder inside of the `src` folder called `actions`. Inside of the `actions` folder, there are 3 files:

    > Note: This `actions` folder will eventually contain your CRUD operations, each in a seperate file this time.

    - `register_action.php`
        - This will contain the code that adds a new user to the database:
            1. Check if the passwords match
                - If they don't:
                    - Redirect to the register page (`register.php`)
                    - Display a descriptive error on the register form
                - If they do:
                    - Continue to step 2
            2. Check if the username exists in the database
                - If it does:
                    - Redirect to the register page (`register.php`)
                    - Display a descriptive error on the register form
                - If it doesn't:
                    - Insert a new user into the database (set the value of `logged_in` to `true`)
                    - Set session variables for the user (e.g. 'logged_in' = 'true' and 'username' = 'joeking', as well as the user id)
                    - Redirect to the application (`index.php`)
        > Note: The passwords will need to be hashed before saving it to the database. To do that, use PHP's `password_hash()` function, and make sure to use the current default, bcrypt. DO NOT use `'sha1'`, `'sha2'`, or `'md5'`, as these are no longer cryptographically secure for passwords. If you would like to read more on best security practices for storing passwords, [this article on hashing security](https://crackstation.net/hashing-security.htm) is one of my favorites.
    - `login_action.php`
        - This will contain the code that's associated with logging in:
            1. Check if the username exists in the database
                - If it doesn't:
                    - Redirect to the login page (`login.php`)
                    - Display a descriptive error on the login form
                - If it does:
                    - Continue to step 2
            2. Check if the password in the database matches the hashed password that the user provided
                - If it doesn't:
                    - Redirect to the login page (`login.php`)
                    - Display a descriptive error on the login form
                - If it does:
                    - Set session variables for the user (i.e. `'logged_in' = 'yes'` and `'username'='mike'`, as well as the user's `id`)
                    - Update the `logged_in` variable in the database
                    - Redirect to the application (`index.php`)
    - `logout_action.php`
        - This will contain the code that's associated with logging out:
            1. Update the `'logged_in'` variable in the database
            2. Destroy any session variables
            3. Redirect to the login page (`login.php`)
            4. Add a <kbd>Log Out</kbd> button on the `<nav>` bar in your `index.php` file that runs `logout_action.php` when clicked.

2. In your project, there is a folder inside of the `src` folder called `views`. Inside of the `views` folder, there are 2 files:
    - `register.php`
        - This will contain a form to register a new user
        - The form needs to ask for a username and a password, and also a field to confirm the password
        - The `action` attribute of the `<form>` tag will be the relative path to the `register_action.php` page you created
    - `login.php`
        - This will contain a form to log in as an existing user
        - The form needs to ask for a username and a password
        - The `action` attribute of the `<form>` tag will be the relative path to the `login_action.php` page you created

3. Add some logic to `index.php` that only shows your application if the user is logged in
    - If they aren't logged in, redirect them to the `login.php` page automatically. This is typically done by checking for session variables and redirecting to a login page if those variables are missing

# Tips

## MySQL OOP in PHP

PHP has two built-in ways of making MySQL queries: Object-Oriented, and Procedural. You will often see both across the internet, [sometimes on the same page](https://www.w3schools.com/Php/func_mysqli_select_db.asp). The way to distingush the two is that OOP has the arrow operator, i.e. `->foo($)`, while Procedural function calls don't. __You will be using the OOP style.__ If you find documentation that has Procedural style, it's fairly easy to guess how it should be in OOP:

```php
// change from this
mysqli_foo($conn, $bar, $baz); // BAD
```

```php
// to this
$conn->foo($bar, $baz); // GOOD
```

## Prepared Statements

You are required to use prepared statements to access the database. This basically means instead of writing `"SELECT * WHERE username = $username"`, we say `"SELECT * WHERE username = ?"` and then use some mysqli methods to replace the question mark with `$username`. This makes it so a user can't enter the username [`Robert'); DROP TABLE user;--`](https://xkcd.com/327/) and we lose our whole table. Check the article linked on prepared statements for how to use them and why they're important.

## Refactoring

In getting your action files to work, there are many ways to do these actions. For example, when you're checking to see if a username is taken, you can do one of the following:

1. Query the database, and if a record comes back, throw an error.
2. Make the `username` field unique beforehand, and try to insert a new record with the username, and if no new records were created, throw an error.

Either way will work and are good, but the flow of logic (i.e. on a UML diagram) is different. If the way you implement the functionality is different from the way you planned on your UML, change your UML and submit it again, with a note saying why you changed it.

## Where can I see PHP Errors?

A good practice is to make sure errors are reported while developing. You can accomplish this by adding the following line to the top of any PHP page where you need to see errors:

```php
error_reporting(-1);
```

This will print errors to the browser window as HTML.

Make sure to change it back before pushing to production:

```php
error_reporting(0);
```

## Reasons for MySQL Connection Failure

Here are a few common reasons for a MySQL Connection Failure:

### `Connection refused`

The database isn't set up yet. This should only happen in your development environment. It just means that MySQL server hasn't finished being set up yet. Usually it will go away after 5 minutes.

### `No such file or directory`

The `$mysql_servername` isn't correct. It should be `mariadb` on dev, and `localhost` on production. Use `echo` to see the value of the environment variable, and get it changed in your `.env` file.

### `Access denied for user 'developer'@'192.168.1.10' (using password: YES)`

The `$mysql_password` isn't correct. If you remember the password, change it in your `.env`. If you forgot your password, you may need to reset your user or your database.

## `or die();`

For each PHP statement that does anything with the database you should have an `or die()` next to it so if it errors, you will know it. You do this by adding `or die("Error message");` at the end of your PHP statements (before the `;`). You can also print the MySQL error in the die statement. Here's an example.

```php
$conn->select_db("lab_3") or die("Failed to connect to db: " . $conn->connect_error);
```

## Code Complexity

Many times you might need to check a condition in order to continue in your file. For example, in your `register_action.php`, it might look something like this:

```php
if (/* database connected */) {
    if (/* passwords match */) {
        if (/* username isn't taken */) {
            /* Do register */
        }
    }
}
```

Nested conditionals add to code complexity and they're ugly. Let's look at a better way, which is less complex, prettier, and is easier to find any mistakes:

```php
if (/* database NOT connected */) {
    die();
}
if (/* passwords DON'T match */) {
    die();
}
if (/* username IS taken */) {
    die();
}
/* Do register */
```

You will not be graded on this, but it is good practice to reduce code complexity, especially when your code has `else` blocks.

## phpMyAdmin 404 Not Found Error on Production Environment

For some reason, sometimes phpMyAdmin gets a 404 Not Found Error. Complete these steps and it should start to work!

- Open your enabled site using your favorite editor

    ```sh
    sudo nano /etc/apache2/sites-available/it210_lab.conf
    ```

- Then add the following line at the very bottom:

    ```
    Include /etc/phpmyadmin/apache.conf
    ```

- Then run the following commands:

    ```sh
    sudo ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf

    sudo a2enconf phpmyadmin.conf

    sudo service apache2 reload
    ```

## Apache-php error when running `docker-compose up -d`

When you run `docker-compose up -d` you might get an apache-php error that says **"Message": "Unhandled exception: Filesharing has been cancelled"**. If you do get this error, there is a simple fix to it. You just need to add your project folder to docker's file sharing settings. To do this, click on the docker icon on the task bar to bring up a menu bar. On the menu bar, go to Settings > Resources > File Sharing. Add the path to your project folder by clicking on the blue plus button and navigating to your project folder. Click Apply & Restart, then run `docker-compose up -d` again. 

## How can I see my session variables?

Session variables cannot be seen in the developer console. In order to see what variables are stored in your current session you need to dump them to a webpage. This can be done by by adding the following code:

```php
<?php var_dump($_SESSION); ?>
```

## Error Log on Production Environment

If you get "Internal Server Error" check the apache log files. Simple way is to run `tail /var/log/apache2/error.log` which will give you the last few lines of the log (most recent).

## `header()` & `session_start()` functions and errors

These two functions MUST come before anything is written to the output stream. This means they come before anything is `echo`ed or written outside of the `<?php` or `?>` tag. This includes any white space (space, new line, etc.) outside of the `<?php` tag. Check the beginning of your scripts including any included scripts. The best way to think of how to use this is to put them at the top of the script as much as you can. You will get an error otherwise.

## `mysqli::real_escape_string()`

Although it is not required, it is good to know and understand sanitizing user inputs. Whenever an input from GET or POST or whatever is being used to go into a SQL statement should be sanitized (escaped). Use this function to do that. Example...

```php
$query = "SELECT * FROM user WHERE username = '". $mysqli->real_escape_string($_GET['name']) ."'";
```

## Hashed password format

When you use the `password_hash()` function, the current default is bcrypt. This is a secure algorithm, for now. In the future, this will probably change, and PHP's default algorithm will change as well. The format of the result of bcrypt is `$algorithm$iterations$generatedSaltGeneratedHash`. An example of this is `$2y$10$GXRIH4gu37ZCFJ/Rc91.xu7dY5K8RVjZS30pOJxurOz3y71O5ncVa`. In our case, `2y` is the algorithm, which was run `10` times. The salt is 22 characters long, or in our case, the string `GXRIH4gu37ZCFJ/Rc91.xu`, and the resulting hash is the remaining 31 characters, `7dY5K8RVjZS30pOJxurOz3y71O5ncVa`. This is in this format so the verification function can take just this one string and a test password, hash it in the exact same way with the same salt, and verify they are the same.

The output of this is a fixed length. However, the default standard may change, and the output of that hash algorithm may be different in length. This is why we left our password field capable of being as long as 255 characters, instead of a fixed 60.

# PHP – Part 1 Pass-off

## Basic Requirements - 20 Points
- [ ] Choose appropriate permissions for all your files (you must explain why – 777 is not appropriate)
- [ ] UML diagram
- [ ] Set up database and created new database user with correct privileges
- [ ] Code is backed up on GitHub and site is live on production server

## Functionality - 25 Points
- [ ] Can register new user
- [ ] No duplicate usernames allowed in database
- [ ] Passwords are hashed in the database  
- [ ] `login_action.php` authenticates, creates a session, and changes database login entry to `true`
- [ ] `logout_action.php` kills the session and changes the database login entry to `false`  
- [ ] User can log in and see protected PHP pages and user can log out 
- [ ] User can NOT see protected PHP pages

## Code - 25 Points
- [ ] HTML5 Form validation on all forms (HTML `required` attribute)  
- [ ] Form actions point to an *external* PHP page (i.e. `*_action.php`)
- [ ] There is no JavaScript in the entire website

## Full Requirements - 30 Points
- [ ] 10 Points - Tell the user when login/register fails in your `login.php`/`register.php`
- [ ] 10 Points - PHP code uses Object Oriented processes
- [ ] 10 Points - PHP code uses SQL prepared statements for accessing the Database
  - `.php` files use bound parameters when querying the database with user-supplied values

## UML Diagram
- [ ] UML Diagram in digital from, showing functionality of registering a new user

# Extra Credit

> Note: TAs cannot help you with extra credit!

- [ ] 10 Points - Use PHP to escape user input before submitting to the database in order to prevent JavaScript XSS attacks

# Writeup Questions

- Describe how cookies are used to keep track of the state. (Where are they stored? How does the server distinguish one user from another? What sets the cookie?)
<!-- - Name 2 advantages of using a server-side database/web-app instead of a browser-only app (like Lab 2). -->
<!-- - Describe how prepared statements protect against sql injection, but not xss. -->
