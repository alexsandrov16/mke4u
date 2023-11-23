## Definición de rutas

Las rutas más básicas de **Mk4U** aceptan un URI y un cierre, proporcionando un método muy sencillo y expresivo de definir rutas, sin complicados archivos de configuración de enrutado:

```php
$route = new Mk4U\Router\RouteCollection();

$route->get('/',[MyController::class]);

echo Mk4U\Router\Router::resolve($route);
```

En **Mk4U** se pueden definir las rutas de dos maneras.

1. Especificando cada ruta a través de los métodos públicos de la clase ```Mk4U\Router\RouteCollection```:
```php
$route->get('/',[MyController::class]);

$route->get('/(:any)',function(Mk4U\Http\Request $request, Mk4U\Http\Response $response, $sld){
    return $response::plain("Hola $sld");
});
```

2. Mediante un arregle que contenga todas las rutas:
```php
$arr = [
    '/' => [
        "method"=>'GET',
        "action"=> [MyController::class]
    ],
    '/(:any)'=> [
        "method"=>'POST',
        "action"=> function ($respose, $request, $myvar) {
            // ...
        }
    ],
    '/example/(:num)'=>[
        "method"=>[
            "GET",
            "POST"
        ],
        "action"=> [MyController::class]
    ]
];

$routes = new Mk4U\Router\RouteCollection;
$route->save($arr);
```

## Métodos de router disponibles

El router le permite registrar rutas que responden a cualquier verbo HTTP:

```php
$route->get($uri, $callback);
$route->post($uri, $callback);
$route->put($uri, $callback);
$route->delete($uri, $callback);
```
A veces es posible que necesite registrar una ruta que responda a múltiples verbos HTTP. Usted puede hacerlo usando el método ```Mk4U\Router\RouteCollection::map()```

```php
$route->map(['get','post'], '/', function($respose, $request){
    // ...
});
```

### Clousure

Puede usar una función anónima, o Cierre, como el destino al que se asigna una ruta. Esta función será ejecutada cuando el usuario visita ese URI. Esto es útil para ejecutar rápidamente tareas pequeñas, o incluso para mostrar una vista sencilla:

```php
$route->get('/', function($respose, $request){
    //Tu código
});
```

### Controlador

También puede usar una matriz con los valores del controlador y el método a instanciar como destino de la ruta asigna. Normalmente esta matriz acepta dos posiciones siendo la primera posición el controlador a ser instanciado y la segunda posición el método a ser cargado.

```php
$route->get('/', [Foo\Bar\MyController::class,'index']);
```

El método ```index()``` del controlador definido se cargara de manera predeterminada solamente definiendo una matriz de una sola posición.

```php
$route->get('/', [Foo\Bar\MyController::class]);
$route->get('/', ['Foo\Bar\AnotherController']);
```

### Agrupar rutas

Puedes agrupar tus rutas bajo un nombre común con el método ```group()``. Esto le permite reducir la escritura necesaria para construir un amplio conjunto de rutas que comparten la cadena de apertura, como cuando se construye un área de administración:

```php
$route->group('/admin', function($route)
{
    $route->get('/blogs', function($respose, $request){
        $response::html('ADMIN DASHBOARD');
    });
    $route->get('/users', [Foo\Bar\Controller::class,'user']);
});
```

## Espacio de nombre

Puede especificar un espacio de nombres diferente para ser utilizado en cualquier controlador, con el método ```Mk4U\Router\RouteCollection::namespace()```.
```php
$route->namespace('Foo\Bar\Baz');
$route->get('/users', ['Controller','user']);
```


## Marcadores de posición

A veces necesitará capturar segmentos del URI dentro de su ruta. Por ejemplo, es posible que deba capturar la ID de un usuario de la URL. Puede hacerlo definiendo parámetros de ruta como marcadores de posición:

```php
$route->get('/user/(:num)', function($request, $response, $id){
    return $response::json(["User id"=> $id]);
});
```

Puede definir tantos parámetros de ruta como requiera su ruta:

```php
$route->get('/posts/(:num)/comments/(:alpha)', function($request, $response, $postId, $comment){
    //Tu código
});
```

Los parámetros de ruta siempre serán representados como marcadores de posición, estos se inyectan en las devoluciones de llamada/controladores de ruta en función de su orden; los nombres de los argumentos de devolución de llamada/controlador de ruta no importan. 

_Es importante señalar que siempre se deben pasar como parametros de las devoluciones de llamada/controlador los objetos ```Mk4U\Http\Request``` y ```Mk4U\Http\Response``` para el manejo de las solicitudes y respuestas HTTP._

Los marcadores de posición son simplemente cadenas que representan un patrón de expresión regular durante el proceso de enrutamiento, estos marcadores de posición se reemplazan  con el valor de la expresión regular.

A continuación los marcadores de posición que están disponibles para su uso en rutas:

|Marcadores  |Descripción                                    |
|------------|-----------------------------------------------|
|(:any)      |Adminte cualquier carácter excepto la barra(/) |
|(:alphanum) |Admite caracteres alfanuméricos                |
|(:num)      |Admite caracteres numéricos                    |
|(:alpha)    |Admite caracteres alfabéticos                  |

## Creación de rutas como matriz de datos

En lugar de definir las rutas una por una puede pasarlas todas como una de datos asociativos a través del método de la clase ```Mk4U\Router\RouteCollection::save()```.

```php
$route_array = [
    '/' => [
        "method"=>'GET',
        "action"=> [MyController::class]
    ],
    '/(:any)'=> [
        "method"=>'POST',
        "action"=> function ($respose, $request, $myvar) {
            //code
        }
    ],
    '/example/(:num)'=>[
        "method"=>[
            "GET",
            "POST"
        ],
        "action"=> [MyController::class]
    ]
];

$route = new Mk4U\Router\RouteCollection;

$route->save($route_array);
```

Utilice el método ```Mk4U\Router\RouteCollection::all()``` para visualizar todas las rutas registradas .

```php
$route->all();
```

### Ejecutando tus rutas

Después de configurar todas las rutas, deberá despachar las rutas. Esto se logra a través del método ```Mk4U\Router\Router::resolve()```, este método devuelve una respuesta HTTP lista para ser renderizada.

```php
Mk4U\Router\Router::resolve($route);
```

## Apache - .htaccess

Este es un ejemplo básico de un archivo htaccess. Básicamente, redirige todas las solicitudes a nuestro archivo index.php.

```.htaccess
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php/$1 [L]
```

Guardar como ```.htaccess``` en el mismo directorio que su "archivo raíz".