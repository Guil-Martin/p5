<?php 

class Router
{
    private $url = '';
    private $controller = '';
    private $action = '';
    private $params = [];

    private $RouteControllers = [
        'home',
        'gallery',        
        'news',
        'users'
    ];

    public function dispatch() 
    {
        // Get server URL
        $this->url = $_SERVER["REQUEST_URI"];
       
        $this->url = trim($this->url);           
        $explode_url = explode('/', $this->url);
        $explode_url = array_slice($explode_url, 2);

        // Controller
        if (!empty($explode_url[0]))
        {
            $this->controller = $explode_url[0];
            
            if (in_array($this->controller, $this->RouteControllers)) 
            {
                $this->controller = $this->loadController();

                // Action
                if (!empty($this->controller) && !empty($explode_url[1])) 
                { // If controller file is charged and the action exists

                    $this->action = $explode_url[1];

                    if (method_exists($this->controller, $this->action))
                    { // If method of name action exists on the controller
                        
                        // Optional parameters
                        $this->params = array_slice($explode_url, 2);
                        
                        spl_autoload_register(function ($class) {
                            require_once(ROOT . "Models/$class.php");
                        });

                        call_user_func_array([$this->controller, $this->action], $this->params);
                        return;                       
                    }
                }
            }
        }

        // Default index page
        $this->controller = "home";
        $this->controller = $this->loadController();
        $this->action = "index";
        $this->params = [];
        call_user_func_array([$this->controller, $this->action], $this->params);
        //require_once(ROOT . 'Views/404.php');
    }

    private function loadController() 
    { // Set up the controller        
        $name = $this->controller . 'Controller';
        $file = ROOT . 'Controllers/' . $name . '.php';
        if (file_exists($file))
        { // If file exists, declare the class controller
            require_once($file);
            $controller = new $name();
            return $controller;
        }
        return null;
    }

}