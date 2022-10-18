# zend-laminas-test-case

## Installation

```bash
## Clone code from github and install:

$ cd path/to/install
$ git clone git@github.com:rajeshkurve/sample-case-study.git
$ composer install


## Setup Database
Execute data/schema.sql to crete the database
Update DB credentials and connection string into file -

config/autoload/global.php - update connecton sting 
Rename config/autoload/development.local.php.dist to config/autoload/development.local.php and update db creditials

Test it using PHP's built-in web server:

$ php -S 0.0.0.0:8081 -t public ( or any available port )

visit http://localhost:8081/ 
