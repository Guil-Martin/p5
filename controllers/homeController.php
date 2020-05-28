<?php

class homeController extends Controller
{

    function index(...$data)
    {
        $d = [];


        // Display last members entries


        $this->set($d);
        $this->render("index");
    }

}