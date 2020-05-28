<?php
class Dispatcher {

    private $request;

    public function dispatch() {
        // Get url with request class and set up the controller
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
        $controller = $this->loadController();
        if (isset($controller))
        {

            $methodExists = method_exists($controller, $this->request->action);
            //$viewExists = file_exists(ROOT . "Views/" . ucfirst(str_replace('Controller', '', get_class($controller) . '/' . $this->request->action . '.php')));

            // Checks if method exists
            if ($methodExists)
            {
                call_user_func_array([$controller, $this->request->action], $this->request->params);
            }
            else
            {
                require_once(ROOT . 'Views/404.php');
            }
        } 
        else
        {
            require_once(ROOT . 'Views/404.php');
        }
    }

    public function loadController() {
        // Set up the controller
        $name = $this->request->controller . 'Controller';
        $file = ROOT . 'Controllers/' . $name . '.php';
        if (file_exists($file))
        {
            require_once($file);
            $controller = new $name();
            return $controller;
        }
        return null;
    }

}
?>