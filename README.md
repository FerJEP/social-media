# Social Media
Simple social media website using PHP and MySQL.

- It uses a custom [Router](./src/Router.php) which can handle dynamic routes
- A user can: 
  - create an account with a username, email and password
  - log in either with their email or username
  - set a profile picture
  - post
  - see other users post

### [Figma Design](https://www.figma.com/file/WT36F4dOaKEwkGQvMBbrjG/Social-Media?node-id=0%3A1)

## Requirements
 - [php-imagick](https://www.php.net/manual/en/book.imagick.php)

### MySQL schema
You have the [schema.sql](./schema.sql) that this project expects from your database.

First, log into your mysql and create a database:
```
CREATE DATABASE database_name;
```
Then, log out and type in your terminal: 
```
$ mysql database_name < path_to_schema.sql  
```
If you need to pass user and password to your login, use instead:
```
$ mysql -u my_user -p database_name < path_to_schema.sql  
```

## Environment variables 

You can create a `.env` file in the root of the project. 
This project uses the following env variables:
- `DB_HOST` : host of your database
- `DB_NAME` : name of your database
- `DB_USERNAME` : user to log into the database
- `DB_PASSWORD` : password to log into the database

## Development server

Go to the project public folder:
  ```
  $ cd src/public
  ```
Run php development server
  ```
  $ php -S localhost:8080
  ```
Go to [localhost:8080](http://localhost:8080) in your browser
