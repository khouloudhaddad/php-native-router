# Building an Effective Native PHP Router: A Guide for Experts

Routing is a critical aspect of any web application. While frameworks like Laravel offer sophisticated routing systems, sometimes you need the flexibility and control of a native PHP solution. This article demonstrates how to create a robust and effective router in native PHP, tailored for experts.

---

## Project Structure

Here’s the structure of our native PHP routing project:

```
php-native-router/
├── controllers/
│   ├── HomeController.php
│   ├── UserController.php
├── Router.php
├── index.php
```

Each component serves a specific purpose:

- **`index.php`**: The entry point of the application that initializes and dispatches routes.
- **`Router.php`**: The core routing logic for handling requests.
- **Controllers**: Specific logic for different application areas (e.g., `HomeController`, `UserController`).

---

## The Router Implementation

The `Router` class is the backbone of the application, enabling dynamic route matching and execution. Here’s the implementation:

### `Router.php`
```php
<?php
class Router {
    private array $routes = [];

    public function add(string $method, string $path, callable|array $handler): void {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $requestUri, string $requestMethod): void {
        foreach ($this->routes as $route) {
            $params = [];
            if ($route['method'] === strtoupper($requestMethod) && $this->match($route['path'], $requestUri, $params)) {
                call_user_func_array($route['handler'], $params);
                return;
            }
        }
        // Handle 404
        http_response_code(404);
        echo "404 Not Found";
    }

    private function match(string $routePath, string $requestUri, array &$params): bool {
        $routePattern = preg_replace('/\{([\w]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';

        if (preg_match($routePattern, $requestUri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        return false;
    }
}
```

---

## Defining Routes

Routes are defined in the `index.php` file, where we map specific request methods and URIs to handlers.

### `index.php`
```php
<?php
require_once 'Router.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/UserController.php';

// Initialize the router
$router = new Router();

// Define routes
$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/user/{id}', [UserController::class, 'show']);
$router->add('POST', '/user', [UserController::class, 'create']);

// Dispatch the request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod);
```

---

## Building Controllers

Controllers encapsulate the application logic. Here’s how the `HomeController` and `UserController` are implemented:

### `controllers/HomeController.php`
```php
<?php
class HomeController {
    public static function index(): void {
        echo "Welcome to the homepage!";
    }
}
```

### `controllers/UserController.php`
```php
<?php
class UserController {
    public static function show(string $id): void {
        echo "User ID: " . htmlspecialchars($id);
    }

    public static function create(): void {
        echo "User created!";
    }
}
```

---

## Testing the Router

1. **Start a PHP Server:**
   ```bash
   php -S localhost:8000
   ```
2. **Test the Routes:**
   - Visit `/` for the homepage.
   - Visit `/user/{id}` to see a specific user (replace `{id}` with a value).
   - Use a tool like Postman or `curl` to test `POST /user`.
3. **404 Handling:**
   - Visit a non-existent route to test the 404 response.

---

## Advanced Enhancements

1. **Middleware Support**: Add pre-processing logic for requests.
2. **Controller Integration**: Map routes to controller methods dynamically.
3. **Error Handling**: Customize error responses for better UX.
4. **Caching**: Cache compiled routes to improve performance.
5. **Optional Parameters**: Extend route patterns to handle optional parameters or wildcards.

---

By following this guide, you’ll have a robust, flexible routing system in native PHP. It’s perfect for small projects or as a foundation for learning advanced routing concepts. 
