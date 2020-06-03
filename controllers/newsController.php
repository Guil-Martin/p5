<?php

class newsController extends Controller
{

    function newsCreate(...$data)
    { // AJAX
        $d = [];

        if (!empty($data[0]))
        { // User ID parameter went trough
          // Secure url input
            $data[0] = (int) $data[0];

            if (isset($_POST["title"]))
            {                
                $formD = [];

                $formD = $this->secure_form($_POST);
                $errors = [];

                $formD['newsTitle'] = $_POST['title'];
                $formD['newsContent'] = $_POST['content'];

                if (empty($formD['newsTitle']))
                { // title is empty
                    $errors['titleEmpty'] = 'Veuillez renseigner un titre';
                } 
                else 
                {
                    $titleLen = strlen($formD['newsTitle']);
                    if ($titleLen > 100)
                    { // title too long, less than 30 characters
                        $errors['titleLen'] = 'Le titre est trop long, moins de 100 caractères sont requis.'; 
                    } 
                }

                if (empty($formD['newsContent']))
                { // content is empty
                    $errors['contentEmpty'] = 'Veuillez renseigner un contenu';
                } 
                else 
                {
                }

                if (empty($errors)) 
                { // If everything is good, post news
                    require_once(ROOT . 'Models/NewsManager.php');
                    require_once(ROOT . 'Models/UserManager.php');
                    require_once(ROOT . 'Models/News.php');                    
                    require_once(ROOT . 'Models/User.php');

                    $userManager = new UserManager();
                    $user = $userManager->getUserInfoBy($data[0], 'Id');

                    if (!empty($user))
                    {
                        $newsManager = new NewsManager();

                        // Fill missing info about the user, author of this news
                        $formD['userId'] = $user->getId();
                        $formD['author'] = $user->getUserName();
                        // Creates a news object to add it to the db
                        $news = new News($formD);

                        if ($newsManager->newsCreate($news))
                        { // Should succeed if userId valid
                            $d['Success'] = 'Nouvelle postée avec succès';
                        }
                    }

                }

                $d['Data'] = $formD;
                $d['Errors'] = $errors;

            }
        }
        
        $this->set($d);
        $this->render("newsCreate", true);
    }

    function news(...$data)
    { // AJAX $data[0] = user id / $data[1] = page
        require_once(ROOT . 'Models/UserManager.php');
        require_once(ROOT . 'Models/NewsManager.php');
        require_once(ROOT . 'Models/User.php');
        require_once(ROOT . 'Models/News.php');

        $d = [];

        if (!empty($data[0]))
        { // User ID parameter went trough
          // Secure url input
            $data[0] = (int) $data[0];

            // Get user data if found
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($data[0], 'Id');

            if (!empty($user))
            { // User data found and ready

                // Check if param for page exists
                // Checks if it's a number and greater than 0, else set it to one
                $data[1] = empty($data[1]) ? 1 : (int) $data[1];
                $data[1] = $data[1] < 1 ? 1 : $data[1];

                $newsManager = new NewsManager();
                $news = $newsManager->getNews($user->getId(), $data[1]);

                if (!empty($news))
                { // News existing
                    $d['News'] = $news;
                }
                $d['User'] = $user;
                $d['Owner'] = $this->isUserPageOwner($user->getContentId());
            }
        }

        $this->set($d);
        $this->render("news", true);
    }

    function newsSingle(...$ids)
    { // AJAX
        require_once(ROOT . 'Models/UserManager.php');
        require_once(ROOT . 'Models/NewsManager.php');
        require_once(ROOT . 'Models/User.php');
        require_once(ROOT . 'Models/News.php');
        require_once(ROOT . 'Models/Comment.php');

        $d = [];

        if (!empty($ids[0]))
        { // News ID parameter went trough
          // Secure url input
            $ids[0] = (int) $ids[0];

            $userManager = new UserManager();
            $newsManager = new NewsManager();
            $news = $newsManager->getNewsContent($ids[0]);

            if (!empty($news)) 
            {
                $news = $news[0];

                // Convert BBcode
                /*
                $parser = new ROOT\Vendor\SBBCodeParser\Node_Container_Document();
                $news->setNewsContent($parser->parse($news->getNewsContent())
                ->detect_links()
                ->detect_emails()
                ->detect_emoticons()
                ->get_html());
                */

                // Get author user data
                $author = $userManager->getUserInfoBy($news->getUserId(), 'id');
                
                if (!empty($author))
                { // Author data found
                    $d['Author'] = $author;
                    $d['News'] = $news;                    

                    if (CONNECTED) 
                    { // Get user data if connected

                        $d['Owner'] = $this->isUserPageOwner($author->getContentId());

                        $user = $userManager->getUserInfoBy($_SESSION['userId'], 'id');
                        if (!empty($user))
                        {
                            $d['User'] = $user;
                            $d['NewsLiked'] = $newsManager->liked($news->getId(), $user->getId());
                            
                            ///// Form comment
                            if (isset($_POST['msgContent']))
                            {  
                                $formD = [];
                                $errors = [];

                                $formD = $this->secure_form($_POST);
                                $formD['commentContent'] = $_POST['msgContent'];

                                if (empty($formD['commentContent']))
                                { // message content is empty
                                    $errors['commentContentLen'] = 'Veuillez renseigner un contenu';
                                } 
                                else 
                                {
                                    $contentLen = strlen($formD['commentContent']);
                                    if ($contentLen > 500)
                                    { // message content too long, less than 30 characters
                                        $errors['commentContentLen'] = 'Le contenu du message est trop long, moins de 500 caractères sont requis.'; 
                                    } 
                                }

                                if (empty($errors)) 
                                { // If everything is good, post news

                                    $formD['newsId'] = $news->getId();
                                    $formD['userName'] = $user->getUserName();
                                    $comment = new Comment($formD);

                                    if ($newsManager->createComment($comment))
                                    { // Should succeed if userId valid
            
                                    }
                                }

                                $d['Data'] = $formD;
                                $d['Errors'] = $errors;

                            }
                            /////
                        }
                    }

                    $comments = $newsManager->getComments($ids[0]);
                    if (!empty($comments)) 
                    {
                        $d['Comments'] = $comments;
                    }

                }
            }
        }
        
        $this->set($d);
        $this->render("newsSingle", true);
    }
    
    function likeNews(...$ids)
    { // AJAX
        if (CONNECTED) 
        { // Check if connected and if the id correspond to the session variable
            if (!empty($ids[0]) && !empty($ids[1]))
            {
                // Secure data passed
                $newsId = (int) $ids[0]; // newsId
                $userId = (int) $ids[1]; // userId

                if ($newsId > 0 && $userId > 0) {
                    if ($_SESSION['userId'] === $userId)
                    { // Making sure the connected user correspond to the id in session
                        require_once(ROOT . 'Models/NewsManager.php');
                        $newsManager = new NewsManager();
                        $newsManager->likeNews($newsId, $userId);
                    }
                }
            }
        }        
    }
 
}