<?php

class homeController extends Controller
{

    public function index(...$ids)
    {
        $data = [];


        $this->set($data);
        $this->render("index");
    }

    public function posts(...$filters)
    {
        $data = [];

        $homeManager = new HomeManager();

        if (CONNECTED) 
        {
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($_SESSION['userId'], 'Id');
            if (!empty($user)) {
                $data['User'] = $user;
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
            $data['Data'] = $formD;

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
      
        if (!empty($filters[0])) 
        { // Get back filters data if available on pagination button presses
            $db = $filters[0] === 'news' ? DB_NEWS : ($filters[0] === 'gallery' ? DB_IMG : $db);
            if (!empty($filters[1])) {
                $filters[1] = (int) $filters[1];
                switch ($filters[1]) {
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
            if (!empty($filters[2])) {
                switch ($filters[2]) {
                    case 'views':
                        $number = 'views'; break;
                    case 'likes':
                        $number = 'likes'; break;
                    case 'comments':
                        $number = 'comments'; break;
                    default: break;
                }                
            }
            if (!empty($filters[3])) {
                switch ($filters[3]) {
                    case 'recent':
                        $order = 'DESC'; break;
                    default: break;
                }
            }
        }

        ///// Pagination

        // Get page from page selector form else get it from pagination buttons
        $page = !empty($_POST['pageSelector']) ? (int) $_POST['pageSelector'] : (!empty($filters[4]) ? (int) $filters[4] : 1);
        
        if ($page < 1) { $page = 1; } // Not valid / 0

        $count = $homeManager->numElt($db);               
        $numPerPage = $db == DB_NEWS ? NEWS_PER_PAGE : IMAGES_PER_PAGE; // Number of posts on the page
        $numPages = ceil($count / $numPerPage);
        $data['NumPages'] = $numPages;
        $data['CurrentPage'] = 1;

        $page = $page > $numPages ? $numPages : $page; // Page max?
        $data['CurrentPage'] = $page;
        /////

        $posts = $homeManager->getFiltered($db, $period, $number, $order, $page);

        foreach ($posts as $key => $post) {
            $posts[$key] = $this->secure_form($post);
            $posts[$key]['likes'] = $this->shortNumber($posts[$key]['likes']);
            $posts[$key]['comments'] = $this->shortNumber($posts[$key]['comments']);
            $posts[$key]['views'] = $this->shortNumber($posts[$key]['views']);
        }

        $data['Posts'] = $posts; 

        $currfilters = [];
        $db = $db == DB_NEWS ? 'news' : 'gallery';
        $currfilters['db'] = $db;
        $currfilters['period'] = $period;
        $currfilters['number'] = $number;
        $currfilters['order'] = $order;

        $data['Data'] = $currfilters;

        $this->set($data);
        $this->render("posts", true);
    }
}