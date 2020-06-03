<?php

class aboutController extends Controller
{
    
    function about(...$data)
    { // AJAX
        require_once(ROOT . 'Models/UserManager.php');
        require_once(ROOT . 'Models/News.php');

        $d = [];

        // stats of the user

        $this->set($d);
        $this->render("about", true);
    }

}