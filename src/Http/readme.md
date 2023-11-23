## Uso

El módulo Http viene con un montón de paquetes que te ayudan a gestionar la entrada y salida de tu app o API. Este módulo viene con la funcionalidad relacionada con Http más usada como peticiones, respuestas y demás.

### Cookies

```Mk4U\Http\Cookies``` proporciona una forma orientada a objetos de interactuar con las cookies.

- Establecer cookie.
```php
Cookie::set($name, $value, $expires, $path, $domain, $secure, $httponly, $options);
```

- Recuperar cookie.
```php
return Cookie::get($name);
```

- Recuperar todas las cookies.
```php
return Cookie::all();
```

- Verificar existencia de cookie.
```php
if(Cookie::has($name) == true)
```

- Eliminar cookie.
```php
Cookie::remove($name);
```

### Header

Para el manejo de las cabeceras y mensajes HTTP tanto la clase ```Mk4U\Http\Request``` como ```Mk4U\Http\Response``` hacen uso del trait ```Mk4U\Http\Header```.

- Mostrar Version del Protocolo Http.
```php
$http = new Request;
// ó
$http = new Response;

return $http->getProtocolVersion();
```

- Establecer Version del Protocolo Http.
```php
$http->setProtocolVersion();
```

- Obtener todas las cabeceras.
```php
return $http->getHeaders();
```

- Mostrar cabecera.
```php
return $http->getHeader($name);
```

- Verificar si existe cabecera.
```php
if($http->hasHeader($name) == true)
```

- Establecer cabecera.
```php
$http->setHeader($name, $value);
```

- Establecer todas las cabeceras.
```php
$http->setHeaders($headers);
```

- Establecer todas las cabeceras.
```php
$http->addHeader($name, $value);
```

- Eliminar cabecera.
```php
$http->removeHeader($name);
```

- Devuelve cuerpo del mensaje.
```php
return $http->getBody();
```

- Establece cuerpo del mensaje.
```php
$http->setBody($name);
```

### Request

```Mk4U\Http\Request``` proporciona una forma orientada a objetos de interactuar con la solicitud HTTP actual.

- Obtener solicitud Http.
```php
$request = new Request;
```

- Devuelve superglobal $_SERVER.
```php
//Si no se le pasa ningun parametro devuelve todos los datos de $_SERVER
return $request->server();

//Debe siempre pasarle parametros compatibles con los indices de $_SERVER
//este metodo es insensible a mayúsculas y minúsculas
return $request->server('server_Name');
```

- Recuperar parámetros proporcionados en el cuerpo de la solicitud.
```php
return $request->getParsedBody();
```

- Obtener solicitud de destino.
```php
return $request->getTarget();
```

- Establecer solicitud de destino.
```php
$request->setTarget($target);
```

- Obtener metodo HTTP.
```php
return $request->getMethod();
```

- Establecer metodo HTTP.
```php
$request->setMethod($method);
```

- Verifica el metodo HTTP.
```php
if($request->hasMethod($method) == true)
```

- Recupera la instancia de ```Mk4U\Http\Uri```.
```php
return $request->getUri();
```

- Devuelve una instancia de ```Mk4U\Http\Uri``` con el URI proporcionado.
```php
$request->setUri($uri, $preserv_host);
```

- Muestra todos los valores de la solicitud del mensaje HTTP.
```php
var_dump($request);
```

### Response

```Mk4U\Http\Response``` proporciona una forma orientada a objetos de interactuar con la respuesta HTTP actual.

- Representación de una respuesta saliente del lado del servidor
```php
$response = new Response($content, 200, $headers, $protocolVersion);

//Enviar la respuesta al cliente
echo $response;
```

- Muestra todos los valores de la respuesta del mensaje HTTP.
```php
var_dump($response);
```

- Obtiene el código de estado de respuesta.
```php
return $response->getStatusCode();
```

- Devuelve el código de estado especificado y, opcionalmente, la frase de motivo.
```php
$response->setStatus(200);
//return 200 OK

$response->setStatus(200, 'By Happy');
//return 200 By Happy
```

- Obtiene la frase del motivo de la respuesta asociada al código de estado.
```php
return $response->getReasonPhrase();
```

- Devuelve cuerpo del mensaje como JSON.
```php
$data = ['key'=>'value'];
return $response::json($data);

return $response::json($data, 201);

return $response::json($data, 100, ['Content-Length'=>28]);
```

- Devuelve cuerpo del mensaje como texto plano.
```php
return $response::plain('Hola mundo');

return $response::plain('Hola mundo', 201);

return $response::plain('Hola mundo', 100, ['Content-Length'=>28]);
```

- Devuelve cuerpo del mensaje como HTML.
```php
return $response::html('<h1>Hola mundo</h1>');

return $response::html('<h1>Hola mundo</h1>', 201);

return $response::html('<h1>Hola mundo</h1>', 100, ['Content-Length'=>28]);
```

### Uri

```Mk4U\Http\Uri``` está destinada a representa un URI y a proporcionar métodos para las operaciones más comunes.

- Establecer una URI.
```php
$uri= new Uri('http://example.com/path?query=value#fragmet');

//Devuelve la representación de la URI como texto
echo $uri;
```
- Establecer esquema de la URI.
```php
$uri->setScheme('http');
```

- Establecer host de la URI.
```php
$uri->setHost('localhost');
```

- Establecer puerto.
```php
$uri->setPort(8080);
```

- Establecer la ruta de la URI.
```php
$uri->setPath('/path');
```

- Establecer las consultas de la URI.
```php
$uri->setPath('?query=value');
```

- Establecer fragmento especificado de URI.
```php
$uri->setFragment('#freagment');
```

- Recuperar el componente de esquema de la URI.
```php
return $uri->setScheme();
```

- Recuperar el componente host de la URI.
```php
return $uri->setHost();
```

- Recuperar el puerto.
```php
return $uri->getPort();
```

- Recuperar el componente de path del URI.
```php
return $uri->getPath();
```

- Recuperar la cadena de consulta de la URI.
```php
return $uri->getQuery();
```

- Recuperar el componente de fragmento de la URI.
```php
return $uri->getFragment();
```

- Verificar cadena de consulta de la URI
```php
if($uri->hasQuery() == true)
```

- Verificar el componente de fragmento de la URI.
```php
if($uri->hasFragment() == true)
```