# PHP-CURL-Wrapper

A simple, yet flexible, PHP wrapper for CURL to facilitate web requests.

## 🌟 Features

- **Simple Requests**: Easily make GET and POST requests.
- **Custom Headers**: Support for custom headers.
- **Payload Support**: Send data payloads with POST requests.
- **Proxy Configuration**: Set specific proxy or choose one at random from a predefined list.

## 🚀 Usage

### 1. Basic Initialization

To start making requests, first, initialize the `CURL` class by providing the URL and optionally the request method and headers.

```php
include "path-to-class/CURL.php";

$request = new CURL('https://example.com', 'GET', ['Custom-Header: Value']);
```

### 2. GET Request

A simple GET request can be made as follows:

```php
$response = $request->SEND();
echo $response['BODY'];
```

### 3. POST Request with Data

If you need to send a POST request with data:

```php
$request = new CURL('https://example.com', 'POST');
$request->DATA(['key' => 'value']);
$response = $request->SEND();

echo $response['BODY'];
```

### 4. Custom Headers

You can set custom headers for the request:

```php
$request->HAEDER(['Another-Header: AnotherValue', 'YetAnother-Header: YetAnotherValue']);
```

### 5. Using Proxies

The class provides flexibility in setting up proxies for your requests:

#### Setting a Specific Proxy

```php
$request->PROXY('localhost:8080');
```

#### Opting for a Random Proxy

This uses a random proxy from a predefined list in the class:

```php
$request->RANDOM_PROXY();
```

### 6. Response Handling

Once the request is sent using the `SEND` method, the response is returned as an associative array containing the header, body, and HTTP status code.

```php
$response = $request->SEND();

$header = $response['HEADER'];
$body = $response['BODY'];
$statusCode = $response['CODE'];

echo "HTTP Status: " . $statusCode . "\n";
echo "Header: \n" . $header . "\n";
echo "Body: \n" . $body;
```

Remember to handle potential errors and check the response status before processing the data further.

## 📜 License

This project is open-sourced under the MIT license. Feel free to use, modify, and distribute as you see fit.
