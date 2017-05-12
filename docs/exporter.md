# Exporter

This class handles the image exporting. It takes in the request and handles the response itself.

## Usage

### 1. First initialize the class

```php
$exporter = new FCExportServer\Exporter($config);
```

You can pass in the different configurations here.

Config options are listed below.

### 2. Use the server

```php
$exporter->serve($request);
```

In you controller or request flow of your app use this method and pass in the request.

## Config options

The default options are shown below but you can change any of it and pass it to the constructor to take effect.

```php
$config = [
  // Options for the server
  'server' => [
    // This is where the saved images will exist
    'save_path' => dirname(__FILE__) . '/ExportedImages/',
    // Allow saving to server
    'allow_save' => true
  ],

  // Options for logger and rabbitmq connection
  // Most of these are self explanatory
  'logger' => [
    'host' => 'localhost',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest',

    'exchange_name' => 'export_logger_exchange',
    'exchange_type' => 'topic',
    'queue_name' => 'export_logger_queue',
    'binding_key' => 'log.info',
  ],

  // Headers to add during the download response
  // You can also add your custom headers here
  'headers' => [
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Headers' => '*',
    'Access-Control-Allow-Methods' => ['GET', 'POST'],
  ]
]
```
