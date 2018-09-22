<?php

class Route
{
    public $path, $controller_name, $function_name, $params;

    function __construct($path, $controller_name, $function_name, $params = array(), $secure = false)
    {
        $this->path = $path;
        $this->secure = $secure;
        $this->controller_name = $controller_name;
        $this->function_name = $function_name;
        $this->params = $params;
    }
}

class Router
{

    private $routes = array();

    private function ControllerCheck($controller_name)
    {
        if(!class_exists($controller_name)) {
            echo "Controller not found";
            die;
        }
    }

    private function MethodCheck($controllerObj, $function_name)
    {
        if(!method_exists($controllerObj, $function_name)) {
            echo "Function not found";
            die;
        }
    }

    public function Map($path, $controller_func, $secure)
    {
        $controller_name = explode("/", $controller_func)[0];
        $function_name = explode("/", $controller_func)[1];
        $params = explode("/", $path);
        unset($params[0], $params[1]);
        $route = new Route("/".explode("/", $path)[1], $controller_name, $function_name, $params, $secure);
        array_push($this->routes, $route);
    }

    public function Start()
    {
        $path = !empty($_GET["path"]) ? "/" . explode("/", $_GET["path"])[0] : "/";
        $full_path = !empty($_GET["path"]) ? explode("/", $_GET["path"]) : array();
        foreach ($this->routes as $route) {
            if ($route->path == $path) {
                if($route->secure) {
                    if(!isset($_SESSION["user"])) {
                        echo "<h1>Permision denied</h1>";
                        header("Location: /login");
                        die;
                    }
                }
                require_once $_SERVER['DOCUMENT_ROOT'] ."/app/controllers/$route->controller_name.php";
                $this->ControllerCheck($route->controller_name);
                $controller = new $route->controller_name;
                $this->MethodCheck($controller, $route->function_name);
                $method_params = array();
                foreach($route->params as $id => $param) {
                    if(!empty($full_path[$id-1])) {
                        array_push($method_params, $full_path[$id-1]);
                    } else {
                        array_push($method_params, null);
                    }
                }
                call_user_func_array(array($controller, $route->function_name), $method_params);
                die;
            }
        }
        echo "<h1>Page not found</h1>";
    }
}

?>