<?php

namespace App\core;

/**
 * Class Router
 * @package App\core
 * @author Abdelali Abouelhassan
 */

class Router
{


    protected array $routes = [];
    public Request $request;
    public Response $response;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }





    public function get($path, $callback)
    {

        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {

        $this->routes['post'][$path] = $callback;
    }


    public function resolve()
    {
        $path  =   $this->request->getPath();
        $method = $this->request->getMethod();

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            if (is_string($callback)) {
                return $this->runderView($callback,$data = []);
            } else if (is_callable($callback)) {
                return $this->runCallback($callback);
            } else if (is_array($callback)) {
                return  $this->runController($callback);
            }
        } else {
            $this->response->setStatusCode(404);
            return 'page not found 404';
        }
    }
    public function runCallback($callback)
    {
        return call_user_func($callback);
    }
    public function runderView($view, $data = [])
    {
        $layoutContnet = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $data);
        $content = str_replace('{{content}}', $viewContent, $layoutContnet);
        return $content;
    }

    public function layoutContent()
    {
        $layout = Application::$app->controller->getLayout();
        ob_start();
        include_once Application::$DIR_ROOT .  "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    public function renderOnlyView($view, $data)
    {
     
        $data  = $data;
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$DIR_ROOT .  "/views/$view.php";
        return ob_get_clean();
    }

    public function runController($callback)
    {

        Application::$app->controller = new $callback[0]();
        $callback[0]    = Application::$app->controller;
        return call_user_func($callback, $this->request);
    }
}
