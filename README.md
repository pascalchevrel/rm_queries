# rm_queries
Dynamic bugzilla queries with product-details data 

This is the code behind the https://trainqueries.herokuapp.com/ domain.

Pages:
- https://trainqueries.herokuapp.com/
- https://trainqueries.herokuapp.com/stores/

### Requirements:

The requirements are very simple, no database, no framework.

- Linux (should work fine on macOS and Windows too, but I didn't test it)
- PHP >=8.4 (Default in Ubuntu 25.04, Fedora 42, Manjaro 25.0) with the `ext-mbstring`, `ext-intl`, `ext-curl`, `ext-dom` extensions
- [Composer](https://getcomposer.org/) to install dependencies

The application is set up by default to be deployed on Heroku with Apache but there is no need to install Apache for development work, the PHP built-in development server is fine for that purpose.

### Installation

1. Clone this repository
2. Install dependencies: `composer install`
3. Start the PHP development server in a terminal either by launching the `./run` bash script or with this command:<br>
  `php -S localhost:8082 -t public/`

The website will be available at http://localhost:8082

If you want to set the site up with an Apache virtual host, make it point to the `public` folder and make sure that the `cache` folder is writable by Apache.