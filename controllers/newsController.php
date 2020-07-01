<?php

class newsController extends Controller
{

    public function newsCreate(...$ids)
    { // AJAX
        $data = [];

        if (!empty($ids[0]))
        { // User ID parameter went trough
          // Secure url input
            $userId = (int) $ids[0];

            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($ids[0], 'Id');

            if (!empty($user))
            {
                $validUser = $this->isUserPageOwner($user);
                $data['Owner'] = $validUser;

                if ($validUser) {

                    $data['User'] = $user;

                    if (isset($_POST["postTitle"]))
                    {                
                        $formD = [];

                        $formD = $_POST;
                        $errors = [];

                        $formD['newsTitle'] = $formD['postTitle'];
                        $formD['newsContent'] = $formD['postContent'];

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
                            $newsManager = new NewsManager();

                            // Fill missing info about the user, author of this news
                            $formD['userId'] = $user->getId();
                            $formD['author'] = $user->getUserName();
                            // Creates a news object to add it to the db
                            $news = new News($formD);

                            if ($newsManager->newsCreate($news))
                            { // Should succeed if userId valid
                                $data['Success'] = 'Nouvelle postée avec succès';
                            }
                        }

                        $formD = $this->secure_form($formD);
                        
                        $data['Data'] = $formD;
                        $data['Errors'] = $errors;

                    }                    
                }
            }
        }
        
        $this->set($data);
        $this->render("newsCreate", true);
    }

    public function newsEdit(...$ids)
    { // AJAX
        $data = [];

        if (!empty($ids[0]) && !empty($ids[1]))
        {
            $userId = (int) $ids[0];
            $newsId = (int) $ids[1];
            
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'Id');

            if (!empty($user))
            {
                $data['User'] = $user;

                $validUser = $this->isUserPageOwner($user);
                $data['Owner'] = $validUser;

                if ($validUser) {

                    $newsManager = new NewsManager();
                    $news = $newsManager->getNewsContent($newsId);

                    if (!empty($news[0])) {
                        $news = $news[0];

                        $data['News'] = $news;

                        $formD['author'] = $news->getAuthor();
                        $formD['title'] = $news->getNewsTitle();
                        $formD['content'] = $news->getNewsContent();
                        $formD['userId'] = $user->getId();
                        $formD['dateEdited'] = $news->getDateEdited();

                    }

                    if (isset($_POST["postTitle"]))
                    {                
                        $formD = [];

                        $formD = $_POST;
                        $errors = [];

                        $formD['title'] = $formD['postTitle'];
                        $formD['content'] = $formD['postContent'];

                        if (empty($formD['title']))
                        { // title is empty
                            $errors['titleEmpty'] = 'Veuillez renseigner un titre';
                        } 
                        else 
                        {
                            $titleLen = strlen($formD['title']);
                            if ($titleLen > 100)
                            { // title too long, less than 30 characters
                                $errors['titleLen'] = 'Le titre est trop long, moins de 100 caractères sont requis.'; 
                            } 
                        }

                        if (empty($formD['content']))
                        { // content is empty
                            $errors['contentEmpty'] = 'Veuillez renseigner un contenu';
                        } 
                        else 
                        {
                        }

                        if (empty($errors)) 
                        { // If everything is good, post news

                            $news->setNewsTitle($formD['title']);
                            $news->setNewsContent($formD['content']);

                            if ($newsManager->newsUpdate($news))
                            { // Should succeed if userId valid
                                $d['Success'] = 'Nouvelle mise à jour avec succès';
                            }
                        }
                        
                        $data['Errors'] = $errors;

                    }
                    
                    $formD = $this->secure_form($formD);
                    $data['Data'] = $formD;
                }
            }
        }
        
        $this->set($data);
        $this->render("newsEdit", true);
    }

    public function news(...$ids)
    { // AJAX

        $data = [];

        if (!empty($ids[0]))
        { // User ID parameter went trough
          // Secure url input
            $userId = (int) $ids[0];

            // Get user data if found
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'Id');

            if (!empty($user))
            { // User data found and ready
                $newsManager = new NewsManager();

                if (!empty($_POST['pageSelector'])) 
                { // Get page from page selector form
                    $ids[1] = (int) $_POST['pageSelector'];
                }                

                // Pagination
                $page = !empty($ids[1]) ? (int) $ids[1] : 1;
                if ($page < 1) { $page = 1; } // Not valid / 0

                $count = $newsManager->numElt($userId);               
                $numPerPage = NEWS_PER_PAGE; // Number of posts on the page
                $numPages = ceil($count / $numPerPage);
                $data['NumPages'] = $numPages;
                $data['CurrentPage'] = 1;

                $page = $page > $numPages ? $numPages : $page; // Page max?
                $data['CurrentPage'] = $page;     

                $news = $newsManager->getNews($user->getId(), $page);

                foreach ($news as $key => $value) 
                { // Compact numbers
                    $news[$key]->setComments($this->shortNumber($news[$key]->getComments()));
                    $news[$key]->setLikes($this->shortNumber($news[$key]->getLikes()));
                    $news[$key]->setViews($this->shortNumber($news[$key]->getViews()));
                }

                if (!empty($news))
                { // News existing
                    $data['News'] = $news;
                }
                $data['User'] = $user;
                $data['Owner'] = $this->isUserPageOwner($user);
            }
        }

        $this->set($data);
        $this->render("news", true);
    }

    public function newsSingle(...$ids)
    { // AJAX
        $data = [];

        if (!empty($ids[0]))
        { // News ID parameter went trough
          // Secure url input
            $newsId = (int) $ids[0];

            $userManager = new UserManager();
            $newsManager = new NewsManager();
            $news = $newsManager->getNewsContent($newsId);

            if (!empty($news)) 
            {
                $news = $news[0];

                // Add 1 view
                $newsManager->addOne($news->getId());

                // Convert BBcode
                //$news->setNewsContent($this->ParseBBCode($news->getNewsContent()));

                // Compact numbers
                $news->setComments($this->shortNumber($news->getComments()));
                $news->setLikes($this->shortNumber($news->getLikes()));
                $news->setViews($this->shortNumber($news->getViews()));

                // Get author user data
                $author = $userManager->getUserInfoBy($news->getUserId(), 'id');
                
                if (!empty($author))
                { // Author data found
                    $data['Author'] = $author;
                    $data['News'] = $news;                    

                    $commentManager = new CommentManager();
                    
                    if (CONNECTED) 
                    { // Get user data if connected

                        $data['Owner'] = $this->isUserPageOwner($author);

                        $user = $userManager->getUserInfoBy($_SESSION['userId'], 'id');
                        if (!empty($user))
                        {
                            $data['User'] = $user;
                            $data['NewsLiked'] = $newsManager->liked($news->getId(), $user->getId());
                            
                            ///// Form comment
                            if (isset($_POST['msgContent']))
                            {  
                                $formD = [];
                                $errors = [];

                                $formD = $_POST;
                                $formD['commentContent'] = $formD['msgContent'];

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

                                    $formD['postId'] = $news->getId();
                                    $formD['userId'] = $user->getId();
                                    $formD['userName'] = $user->getUserName();                                    
                                    $comment = new Comment($formD);

                                    if ($commentManager->createComment($comment, DB_COMMENTS_NEWS))
                                    { // Should succeed if userId valid
                                        $newsManager->addOne($news->getId(), 'comments');
                                    }
                                }

                                $data['Data'] = $formD;
                                $data['Errors'] = $errors;

                            }
                            /////
                        }
                    }

                    // Set up comments
                    $userId = 0;
                    if (!empty($user))
                    { // To check liked comments by the user
                        $userId = $user->getId();
                    }

                    $comments = $commentManager->getComments($newsId, $userId, DB_COMMENTS_NEWS);
                    
                    if (!empty($comments)) 
                    {
                        foreach ($comments as $key => $com) {
                            // Secure data
                            $comments[$key]['commentContent'] = $this->secure_input($com['commentContent']);
                            $comments[$key]['likes'] = $this->shortNumber($comments[$key]['likes']);
                        }
                        $data['Comments'] = $comments;
                    }

                }
            }
        }
        
        $this->set($data);
        $this->render("newsSingle", true);
    }
    
    public function likeNews(...$ids)
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
                        $newsManager = new NewsManager();
                        $newsManager->likeNews($newsId, $userId);
                    }
                }
            }
        }        
    }

    public function delPost(...$ids)
    { // AJAX
        if (!empty($ids[0]) && !empty($ids[1]))
        {
            // Secure data passed
            $newsId = (int) $ids[0]; // newsId
            $userId = (int) $ids[1]; // userId

            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'id');

            if ($this->isUserPageOwner($user))
            { // Making sure the connected user correspond to the id in session
                $newsManager = new NewsManager();
                $newsManager->deletePost($newsId);
            }
        }    
    }

    ///// Comment
    public function likeComment(...$ids)
    { // AJAX
        if (!empty($ids[0]) && !empty($ids[1]))
        {
            // Secure data passed
            $comId = (int) $ids[0]; // comId
            $postId = (int) $ids[1]; // postId
            $userId = (int) $ids[2]; // userId

            if ($this->isUserOwner($userId))
            { // Making sure the connected user correspond to the id in session
                $commentManager = new CommentManager();
                if ($commentManager->likeComment($comId, $postId, $userId, DB_COMMENTS_NEWS)) {
                    $this->addOne($postId, 'likes', '-');
                }
            }
        }    
    }

    public function delComment(...$ids)
    { // AJAX
        if (!empty($ids[0]) && !empty($ids[1]))
        {
            // Secure data passed
            $comId = (int) $ids[0]; // comId
            $postId = (int) $ids[1]; // postId
            $userId = (int) $ids[2]; // userId

            if ($this->isUserOwner($userId))
            { // Making sure the connected user correspond to the id in session
                $commentManager = new CommentManager();
                if ($commentManager->deleteComment($comId, $postId, $userId, DB_COMMENTS_NEWS)) {
                    $this->addOne($postId, 'comments', '-');
                }
            }
        }    
    }
    /////

}