## How to setup in local


```bash
# clone from repo
$ git clone https://github.com/eli-kristina/laravel-api.git

# move to the cloned repo
$ cd laravel-api

# update dependencies
$ composer update

# rename .env.example to .env
# change:
#   DB_DATABASE=dbname 
#   DB_USERNAME=dbuser 
#   DB_PASSWORD=dbpassword
$ php artisan key:generate

# migrate database
$ php artisan migrate
$ php artisan db:seed

# run application in local
$ php artisan serve

# run testing in local
$ composer test
```


## API collection

- check file Keda API.postman_collection.json