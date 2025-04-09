# Symfony todo application

This simple Symfony project has been created to demonstrate how to work with Turbo Streams.


## Requirements
* PHP 8.2 or higher
* Symfony CLI binary
* Docker Compose
* NPM

## Installation
* Clone the repository to your computer
```
git clone git@github.com:maxwellzp/symfony-turbo-stream-todo.git
```
* Change your current directory to the project directory
```
cd symfony-turbo-stream-todo
```
* Install Composer dependencies
```
composer install
```
* Install node modules and build them in dev
```
npm install
npm run dev
```

* Start PostgreSQL server in Docker container
```
docker compose up -d
```

* Create a new database
```
symfony console doctrine:database:create --if-not-exists
```
* Execute Doctrine migrations of the project
```
symfony console doctrine:migrations:migrate --no-interaction
```

* Start Symfony development server
```
symfony server:start -d
```

### Usage
* Access the application in any browser at the given URL https://127.0.0.1:8000/todo


### Tests
* Run test suites
```
./bin/phpunit
```
