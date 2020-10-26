# MySQL Scheman

MySQL schema, a simple MySQL schema synchronization utility. This will create an UPDATE/ADD query automatically based on the schema difference in the database and schema file.

![Build](https://github.com/shabeer-ali-m/mysql-scheman/workflows/PHP%20Composer/badge.svg)

### Why we use MySQL Scheman

Developers find it very hard to synchronize MySQL schema while developing any application. The current conventional method is to create an SQL  file with all the changes & update it in the staging/production server. The main demerit of this method is if there are multiple people and multiple SQL changes on the same table the process is a bit hectic. In order to solve this, we developed a platform where you can update the schema in a file, and by running sync to database it will create the SQL Query for the changes and it will execute.

## Installation
If you use Composer, you can install MySQL Scheman with the following command:
```composer require smart-php/mysql-scheman```

Or alternatively, include a dependency for MySQL Scheman in your composer.json file. For example
```json
{
    "require-dev": {
        "smart-php/mysql-scheman": "dev-master"
    }
}
```

### Config

A database configuration file should be there to communicate with the database. Please see the sample files and create a copy in your working directory with your database credentials.

JSON: config.json
XML: config.xml

### Usage (CLI)

To CLI Help
```sh
./vendor/bin/scheman --help
```

Exporting Database to Schema File:

```sh
./vendor/bin/scheman --config config.json --export yourdatabase.json
```

Sync the file schema with your database

```sh
./vendor/bin/scheman --config config.json --sync yourdatabase.json
```

## License
See the `LICENSE` file.