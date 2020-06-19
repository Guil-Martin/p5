<?php 

class Router
{

    static public function parse($url, $request)
    {

        $url = trim($url);

        if ($url == WEBROOT)
        { // Page by default
            $request->controller = "home";
            $request->action = "index";
            $request->params = [];
        }
        else
        { // Explode URL to get the controller, action and parameters to be handled by the dispatcher
            $explode_url = explode('/', $url);

            // shorten the array
            $explode_url = array_slice($explode_url, 2);

            $request->controller = $explode_url[0];
            $actionExist = isset($explode_url[1]) && !empty($explode_url[1]);
            if ($actionExist) 
            { 
                $request->action = $explode_url[1]; 
            }
            else 
            { 
                $request->action = 'index'; 
            }

            // get parameters if they exist
            $request->params = array_slice($explode_url, 2);

        }
    }
}