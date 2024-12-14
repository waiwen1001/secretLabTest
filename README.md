# Secret-Lab Test

This is a Laravel-based application for managing key-value data storage.

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.2 or higher
- Composer
- MySQL or MariaDB
- Laravel 11

## Setup Instructions

### Install Dependencies

Run the following command to install all the necessary dependencies using Composer:

```bash
composer install
```

## Set Up Environment File

copy the .env.example file to .env:

```bash
cp .env.example .env
```

Open the .env file and set your environment variables, particularly the database configuration:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=secretlab_test
DB_USERNAME=root
DB_PASSWORD=
```

## Create Database
Create the database secretlab_test using MySQL:

```bash
CREATE DATABASE secretlab_test;
```

## Run Database Migrations
Run the database migrations to set up the necessary tables:

```bash
php artisan migrate
```

## Run Unit Tests
To ensure everything is working correctly, run the unit tests:

```bash
php artisan test
```

## Code Coverage
You can view the code coverage results for this project on [Codecov](https://app.codecov.io/github/waiwen1001/secretLabTest/tree/master/).

## API Documentation

### Object API

#### `POST /object`
- **Description**: Store a new object.
- **Request Body**:
  ```json
  {
    "key": "myKey",
    "value": {"userId":1,"id":1,"title":"test title", "body": "testing body content"}
  }
  ```
#### `GET /get_all_records`
- **Description**: Retrieve all records.
- **Response**:
  ```json
  [
    { 
        "key": "myKey", 
        "value": "{\"userId\":1,\"id\":1,\"title\":\"test title\", \"body\": \"testing body content\"}" 
    },
    ...
  ]
  ```
#### `GET /object/{key}`
- **Description**: Retrieve a specific record by key, optionally filtered by timestamp.
- **URL Parameters**:
  - `key` (required): The unique identifier of the record.
- **Query Parameters**:
  - `timestamp` (optional): A Unix timestamp to filter the record by the exact creation time. If provided, it must be numeric.

- **Request Example**:
  ```bash
  GET /object/abc123?timestamp=1638384000
  ```
- **Response (200 OK)**: If the key is found and matches the optional timestamp filter:
  ```json
  {
    "id": "1",
    "key": "myKey",
    "value": "{\"userId\":1,\"id\":1,\"title\":\"test title\", \"body\": \"testing body content\"}",
    "created_at": 1734168037,
    "updated_at": 1734168037
  }
  ```
