<?php 

class Request {

    public $url;

    public function __construct() {
        // Return the url requested by the user
        $this->url = $_SERVER["REQUEST_URI"];

    }

}