## Project Requirements
PHP 7.3+
Composer
Access to CLI

## Installation

Unzip project into youre prefered location.  

Using CLI cd into project directory( where project was unzipped)  

Install dependecies using ### `composer install`  

Install Node dependecies using ### `npm install` 

Create a new file .env  

Copy contents in .env.example into .env  
Update your database details in .env  

run `php artisan key:generate` to generate new app key  
run `php artisan migrate` to migrate tables   
run `php artisan serve` to startup the application  

Visit http://localhost:8000 in your browser to use applicaiton  
