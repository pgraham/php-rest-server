# PHP Rest Server
Request router for RESTful services.

## Quick Start

Install with Composer (zeptech/php-rest-server).

```php
<?php

try {
  $srvr = new \zeptech\rest\RestServer();

  // RequestHandlerImpl must implement \zeptech\rest\RequestHandler
  // \zeptech\rest\BaseRequestHandler can be extended if you only need to handle
  // a subset of the available actions.
  $srvr->addMapping('/', new RequestHandlerImpl());

  if (!empty($_GET)) {
    $server->setQuery($_GET);
  }
  if (!empty($_POST)) {
    $server->setData($_POST);
  }
  $server->setAcceptType($_SERVER['HTTP_ACCEPT']);
  $server->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
  $response = $server->getResponse();

  $headers = $server->getResponseHeaders();
  foreach ($headers as $hdr) {
    header($hdr);
  }
  echo $response;
} catch (Exception $e) {
  error_log($e->getMessage());
  error_log($e->getTraceAsString());
  header('HTTP/1.1 500 Internal Server Error');
  echo $e->getMessage();
}
```
