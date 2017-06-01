## Log Data Builder

It is the data builder for formatting the log request, as all the required options are not provided by the frontend

## Usage

### 1. Initialize the class

```php
$logDataBuilder = new FCExportHandler\LogDataBuilder();
```

### 2. Use the class

```php
$data = $logDataBuilder->build($params, $headers);

// Save $data to your database
```

Pass in the request parameters and request headers to receive the formatted data.
