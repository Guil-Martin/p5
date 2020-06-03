<?php

class homeController extends Controller
{

    function index(...$data)
    {
        $d = [];





        

        $this->set($d);
        $this->render("index");
    }

}