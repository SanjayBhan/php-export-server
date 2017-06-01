# Log Listener

It is the listener for logs sent through rabbitmq by exporter.

## Usage

### 1. Initialize the class

```php
$logListener = new FCExportHandler\LogListener($config);
```

### 2. Use the class

```php
$logListener->consume(function ($data) {

  // Save $data to your database

})->listen();
```

Pass a callback to the consume method which gets the data sent and save it to database.

Then listen to it continuously. 
