<?php

require_once(ROOT . 'Models/HomeManager.php');
require_once(ROOT . 'Models/UserManager.php');
require_once(ROOT . 'Models/User.php');
require_once(ROOT . 'Models/GalleryManager.php');
require_once(ROOT . 'Models/Image.php');
require_once(ROOT . 'Models/NewsManager.php');
require_once(ROOT . 'Models/News.php');

class homeController extends Controller
{

    function index(...$data)
    {
        $d = [];


        $this->set($d);
        $this->render("index");
    }

    function posts(...$data)
    {
        $d = [];

        $homeManager = new HomeManager();

        if (CONNECTED) 
        {
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($_SESSION['userId'], 'Id');
            if (!empty($user)) {
                $d['User'] = $user;
            }
        }       

        $db = DB_IMG;
        $period = 0;
        $number = 'datePosted';
        $order = 'DESC';

        //var_dump($data);
        //var_dump($_POST);

        if (!empty($_POST['type'])) 
        { // When submit filter button is pressed

            $formD = $this->secure_form($_POST);
            $d['Data'] = $formD;

            if ($formD['type'] === "news") {
                $db = DB_NEWS;
            }            

            if ($formD['period'] !== "--") {
                switch ($formD['period']) {
                    case '24':
                        $period = 1; break;
                    case '136':
                        $period = 7; break;
                    case '5040':
                        $period = 30; break;
                    case '60480':
                        $period = 360; break;
                    default: break;
                }                
            }

            if ($formD['number'] !== "--") {
                switch ($formD['number']) {
                    case 'views':
                        $number = 'views'; break;
                    case 'likes':
                        $number = 'likes'; break;
                    case 'comments':
                        $number = 'comments'; break;
                    default: break;
                }                
            }

            if ($formD['order'] !== "--") {
                switch ($formD['order']) {
                    case 'recent':
                        $order = 'DESC'; break;
                    default: break;
                }
            }
        }
      
        if (!empty($data[0])) 
        { // Get back filters data if available on pagination button presses
            $db = $data[0] === 'news' ? DB_NEWS : ($data[0] === 'gallery' ? DB_IMG : $db);
            if (!empty($data[1])) {
                $data[1] = (int) $data[1];
                switch ($data[1]) {
                    case 1:
                        $period = 1; break;
                    case 7:
                        $period = 7; break;
                    case 30:
                        $period = 30; break;
                    case 360:
                        $period = 360; break;
                    default: break;
                }
            }
            if (!empty($data[2])) {
                switch ($data[2]) {
                    case 'views':
                        $number = 'views'; break;
                    case 'likes':
                        $number = 'likes'; break;
                    case 'comments':
                        $number = 'comments'; break;
                    default: break;
                }                
            }
            if (!empty($data[3])) {
                switch ($data[3]) {
                    case 'recent':
                        $order = 'DESC'; break;
                    default: break;
                }
            }
        }

        ///// Pagination

        // Get page from page selector form else get it from pagination buttons
        $page = !empty($_POST['pageSelector']) ? (int) $_POST['pageSelector'] : (!empty($data[4]) ? (int) $data[4] : 1);
        
        if ($page < 1) { $page = 1; } // Not valid / 0

        $count = $homeManager->numElt($db);               
        $numPerPage = $db == DB_NEWS ? NEWS_PER_PAGE : IMAGES_PER_PAGE; // Number of posts on the page
        $numPages = ceil($count / $numPerPage);
        $d['NumPages'] = $numPages;
        $d['CurrentPage'] = 1;

        $page = $page > $numPages ? $numPages : $page; // Page max?
        $d['CurrentPage'] = $page;
        /////

        $posts = $homeManager->getFiltered($db, $period, $number, $order, $page);

        foreach ($posts as $key => $post) {
            $posts[$key] = $this->secure_form($post);
        }

        $d['Posts'] = $posts; 

        $filters = [];
        $db = $db == DB_NEWS ? 'news' : 'gallery';
        $filters['db'] = $db;
        $filters['period'] = $period;
        $filters['number'] = $number;
        $filters['order'] = $order;

        $d['Data'] = $filters;

        $this->set($d);
        $this->render("posts", true);
    }
}