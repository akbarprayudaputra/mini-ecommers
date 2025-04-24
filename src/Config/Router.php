<?php

namespace MiniECommers\Backend\Config;

class Router
{
    protected static array $routes = [];

    public static function get(string $path, string $controller, string $function, array $middleware = [])
    {
        self::$routes[] = [
            "path" => $path,
            "controller" => $controller,
            "function" => $function,
            "middlewares" => $middleware,
            "method" => "GET",
        ];
    }

    public static function post(string $path, string $controller, string $function, array $middleware = [])
    {
        self::$routes[] = [
            "method" => "POST",
            "path" => $path,
            "controller" => $controller,
            "function" => $function,
            "middlewares" => $middleware
        ];
    }

    public static function delete(string $path, string $controller, string $function, array $middleware = [])
    {
        self::$routes[] = [
            "method" => "DELETE",
            "path" => $path,
            "controller" => $controller,
            "function" => $function,
            "middlewares" => $middleware,
        ];
    }

    public static function put(string $path, string $controller, string $function, array $middleware = [])
    {
        self::$routes[] = [
            "method" => "PUT",
            "path" => $path,
            "controller" => $controller,
            "function" => $function,
            "middlewares" => $middleware,
        ];
    }

    public static function run()
    {
        $path = "/";

        if (isset($_SERVER["PATH_INFO"])) $path = $_SERVER["PATH_INFO"];

        $method = $_SERVER["REQUEST_METHOD"];

        foreach (self::$routes as $value) {
            $pattern = "#^" . $value["path"] . "$#";

            if (preg_match($pattern, $path, $variables) && $method == $value["method"]) {
                foreach ($value["middlewares"] as $middleware) {
                    $instance = new $middleware;
                    $instance->before();
                }

                $controller = new $value["controller"];
                $function = $value["function"];

                array_shift($variables);

                call_user_func_array([$controller, $function], $variables);

                return;
            }
        }

        http_response_code(404);
        echo "<h1>404 Not Found</h1>" . PHP_EOL;
    }
}
