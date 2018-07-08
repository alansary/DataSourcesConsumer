# Data Sources Consumer In Laravel
## Description
A consumer that process data from different data sources of varying data formats and insert them into database (without really inserting anything into the database), where consumer shouldn’t know about external data sources (APIs or files) implementation with the ability to add more data sources without changing code implementation of the consumer.

## Installation


#### Dependencies:
* [Laravel 5.6](https://github.com/laravel/laravel)
* [Laravel Excel](https://github.com/Maatwebsite/Laravel-Excel)

**1-** Clone the repository.

```bash
$ git clone https://github.com/alansary/DataSourcesConsumer.git
```

**2-** Run Composer to install or update the requirements.

```bash
$ composer install
```

or

```bash
$ composer update
```

**3-** Create the database and change the credentials and the name of the database in .env file.

```bash
$ cp .env.example .env
```

**4-** Run the following command to generate the secret key and place the secret key in config/app.

```bash
$ php artisan key:generate
```
```php
'key' => env('APP_KEY','You-Generated-Key'),
```

**5-** Migrate the database
```bash
$ php artisan migrate
```

**8-** Run the data seeder (for sample data sources)
```bash
$ php artisan db:seed --class=DataSourcesSeeder
```
**8-** Run the project
```bash
$ php artisan serve
```

----
## APIs:
----

http://127.0.0.1:8000/api/data_sources/create -- POST
#### Request:
```json
{
	"name": "JSON_API",
	"description": "JSON_API",
	"path": "https://jsonplaceholder.typicode.com/posts/"
}
```
#### Response:
```json
{
    "status": true,
    "message": "Data source created successfully",
    "data": {
        "id": 1,
        "name": "JSON_API",
        "description": "JSON_API",
        "path": "https://jsonplaceholder.typicode.com/posts/",
        "updated_at": "2018-07-08 20:21:12",
        "created_at": "2018-07-08 20:21:12"
    }
}
```

http://127.0.0.1:8000/api/data_sources/all -- GET
#### Response:
```json
{
    "status": true,
    "message": "Data sources retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "JSON_API",
            "description": "JSON_API",
            "path": "https://jsonplaceholder.typicode.com/posts/",
            "created_at": "2018-07-08 20:00:53",
            "updated_at": null
        },
        {
            "id": 2,
            "name": "XML_API",
            "description": "XML_API",
            "path": "http://api.plos.org/search?q=title:%22Drosophila%22%20and%20body:%22RNA%22&fl=id,abstract",
            "created_at": "2018-07-08 20:00:53",
            "updated_at": null
        }
    ]
}
```

http://127.0.0.1:8000/api/consumers/getProducts -- GET
#### Returns the data consumed from the data sources.
