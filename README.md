# WarehouseManagerApplication
A small Php test project implementing an application used for managing warehouses and their stock.

## Requirements
- Php >=8.1
- Composer 2

## Installation
Run `composer install` in the root directory and you're good to go

## Usage
The project comes with the following interactive CLI command:
```
bin/console app/warehouse-manager
```

Running this command allows interaction with the application through the following actions:
- Creating new warehouses
- Displaying the current stock information of each warehouse
- Modifying the the stock of one or more warehouses

## Testing
The project comes bundled with a couple PhpUnit tests that asserts most uses of the underlying service class.

You can run the tests with the following command:
```
vendor/bin/phpunit
```