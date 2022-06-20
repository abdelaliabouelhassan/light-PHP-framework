<?php

namespace App\core;


/**
 * Class Application
 * @package App\core
 * @author Abdelali Abouelhassan
 */

class Application
{
    public static string  $DIR_ROOT;
    public Response $response;
    public Router $router;
    public Request $request;
    public Controller $controller;
    public static Application $app;
    public DataBase $db;
    public Session $session;
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new DataBase();
        $this->session = new Session();
        self::$app = $this;
        self::$DIR_ROOT = dirname(__DIR__);
    }

    public function getController(): Controller
    {
        return $this->controller;
    }


    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function run()
    {

        echo  $this->router->resolve();
    }
}
