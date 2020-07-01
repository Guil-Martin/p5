<?php

class galleryController extends Controller
{

    public function galleryCreate(...$ids)
    { // AJAX
        $data = [];

        if (!empty($ids[0]))
        { // User ID parameter went trough
          // Secure url input
            $userId = (int) $ids[0];

            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'Id');

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

                        $formD['imgTitle'] = $formD['postTitle'];
                        $formD['imgContent'] = $formD['postContent'];

                        if (empty($formD['imgTitle']))
                        { // title is empty
                            $errors['titleEmpty'] = 'Veuillez renseigner un titre';
                        } 
                        else 
                        {
                            $titleLen = strlen($formD['imgTitle']);
                            if ($titleLen > 100)
                            { // title too long, less than 30 characters
                                $errors['titleLen'] = 'Le titre est trop long, moins de 100 caractères sont requis.'; 
                            } 
                        }

                        if (empty($formD['imgContent']))
                        { // content is empty
                            $errors['contentEmpty'] = 'Veuillez renseigner un contenu';
                        } 
                        else 
                        {
                        }

                        //// IMAGE
                        if (empty($_FILES['fileUpload']['name'])) {
                            $errors['imageEmpty'] = 'Veuillez séléctionner une image à uploader'; 
                        }
                        else
                        {
                            $file = $_FILES["fileUpload"]["tmp_name"][0];
                            $name = basename($_FILES["fileUpload"]["name"][0]); // basename() may prevent filesystem traversal attacks;
                            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            
                            if (!in_array($extension, array('jpg', 'png', 'jpeg', 'gif')))
                            { // Allow only some formats
                                $errors['imageFormat'] = "Image - Seuls les formats JPG, JPEG, PNG & GIF sont autorisés."; 
                            }
                        }
                        ////

                        if (empty($errors)) 
                        { // If everything is good, post image

                            if (!empty($file))
                            { //// IMAGE
                                $imageThumb = new SimpleImage();
                                // Creates user folder if doesn't exist
                                $target_folder = ROOT . "assets/images/gallery/" . $user->getContentId();
                                if (!is_dir($target_folder))
                                { mkdir($target_folder); }
                                
                                // Rename the image with the title
                                $rename = $this->removeAccents($formD['imgTitle']);
                                $rename = $this->urlValidId($rename)  . '.' . $extension;

                                // Thumbnail
                                $thumb_folder = $target_folder . '/thumbnails/';
                                if (!is_dir($thumb_folder)) 
                                { mkdir($thumb_folder); }

                                $thumb_file = $thumb_folder . '/' . $rename;
                            
                                $imageThumb->load($file);
                                if ($imageThumb->getWidth() > 300) {
                                    $imageThumb->resizeToWidth(300);
                                }
                                if ($imageThumb->getHeight() > 300) {
                                    $imageThumb->resizeToHeight(300);
                                }
                                
                                $formD['ImgThumbnail'] = $user->getContentId() . '/thumbnails/' . $rename;

                                // Main image
                                $imageMain = new SimpleImage();
                                $target_file = $target_folder . '/' . $rename;

                                $imageMain->load($file);
                                if ($imageMain->getWidth() > 1920) {
                                    $imageMain->resizeToWidth(1920);
                                }
                                if ($imageMain->getHeight() > 1080) {
                                    $imageMain->resizeToHeight(1080);
                                }
                                
                                $formD['imgPath'] = $user->getContentId() . '/' . $rename;
                            }

                            $galleryManager = new GalleryManager();

                            // Fill missing info about the user, author of this image
                            $formD['userId'] = $user->getId();
                            $formD['author'] = $user->getUserName();

                            // Creates an image object to add it to the db
                            $image = new Image($formD);

                            if ($galleryManager->galleryCreate($image))
                            { // Should succeed if userId valid
                                $data['Success'] = 'Image postée avec succès';
                                $imageThumb->save($thumb_file);                               
                                $imageMain->save($target_file);                            
                            }

                        }

                        $data['Data'] = $formD;
                        $data['Errors'] = $errors;
                    }
                }
            }
        }
        
        $this->set($data);
        $this->render("galleryCreate", true);
    }

    public function galleryEdit(...$ids)
    { // AJAX
        $data = [];

        if (!empty($ids[0]) && !empty($ids[1]))
        {
            $userId = (int) $ids[0];
            $imageId = (int) $ids[1];
            
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'Id');

            if (!empty($user))
            {
                $data['User'] = $user;

                $validUser = $this->isUserPageOwner($user);
                $data['Owner'] = $validUser;
                
                if ($validUser) {

                    $galleryManager = new GalleryManager();

                    $image = $galleryManager->getImageContent($imageId);

                    if (!empty($image[0])) {
                        $image = $image[0];

                        $data['Image'] = $image;

                        $formD['author'] = $image->getAuthor();
                        $formD['title'] = $image->getImgTitle();
                        $formD['content'] = $image->getImgContent();
                        $formD['userId'] = $image->getId();
                        $formD['dateEdited'] = $image->getDateEdited();

                        $oldImage = ROOT . "assets/gallery/" . $image->getImgPath();
                        $oldImageThumbnail = ROOT . "assets/images/gallery/" . $image->getImgThumbnail();

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

                        //// IMAGE                        
                        if (!empty($_FILES['fileUpload']['name'])) {
                            $file = $_FILES["fileUpload"]["tmp_name"][0];
                            $name = basename($_FILES["fileUpload"]["name"][0]); // basename() may prevent filesystem traversal attacks;
                            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            
                            if (!in_array($extension, array('jpg', 'png', 'jpeg', 'gif')))
                            { // Allow only some formats
                                $errors['imageFormat'] = "Image - Seuls les formats JPG, JPEG, PNG & GIF sont autorisés."; 
                            }
                        }
                        ////

                        if (!empty($file))
                        { //// IMAGE

                            $imageThumb = new SimpleImage();
                            
                            // Creates user folder if doesn't exist
                            $target_folder = ROOT . "assets/images/gallery/" . $user->getContentId();
                            if (!is_dir($target_folder))
                            { mkdir($target_folder); }
                            
                            // Rename the image with the title
                            $rename = $this->removeAccents($formD['title']);
                            $rename = $this->urlValidId($rename)  . '.' . $extension;

                            // Thumbnail
                            $thumb_folder = $target_folder . '/thumbnails/';
                            if (!is_dir($thumb_folder)) 
                            { mkdir($thumb_folder); }

                            $thumb_file = $thumb_folder . '/' . $rename;
                           
                            $imageThumb->load($file);
                            if ($imageThumb->getWidth() > 300) {
                                $imageThumb->resizeToWidth(300);
                            }
                            if ($imageThumb->getHeight() > 300) {
                                $imageThumb->resizeToHeight(300);
                            }

                            $formD['ImgThumbnail'] = $user->getContentId() . '/thumbnails/' . $rename;

                            // Main image
                            $imageMain = new SimpleImage();
                            $target_file = $target_folder . '/' . $rename;

                            $imageMain->load($file);
                            if ($imageMain->getWidth() > 1920) {
                                $imageMain->resizeToWidth(1920);
                            }
                            if ($imageMain->getHeight() > 1080) {
                                $imageMain->resizeToHeight(1080);
                            }

                            
                            $formD['imgPath'] = $user->getContentId() . '/' . $rename;
                        }

                        if (empty($errors))
                        { // If everything is good, post image

                            $image->setImgTitle($formD['title']);
                            $image->setImgContent($formD['content']);

                            if (!empty($imageMain)) 
                            {
                                $image->setImgPath($formD['imgPath']);
                                $image->setImgThumbnail($formD['ImgThumbnail']);
                            }

                            if ($galleryManager->galleryUpdate($image))
                            { // Should succeed if userId valid
                                $data['Success'] = 'Image mise à jour avec succès';

                                if (!empty($imageMain)) 
                                { // Save new images on the server and delete the old ones
                                    if (file_exists($oldImageThumbnail)) {
                                        unlink($oldImageThumbnail);
                                    }
                                    $imageThumb->save($thumb_file);
                                    if (file_exists($oldImage)) {
                                        unlink($oldImage);
                                    }                                    
                                    $imageMain->save($target_file);
                                }

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
        $this->render("galleryEdit", true);
    }

    public function gallery(...$ids)
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
                $galleryManager = new GalleryManager();

                // Pagination
                $page = !empty($ids[1]) ? (int) $ids[1] : 1;
                if ($page < 1) { $page = 1; } // Not valid / 0

                $count = $galleryManager->numElt($userId);               
                $numPerPage = IMAGES_PER_PAGE; // Number of posts on the page
                $numPages = ceil($count / $numPerPage);
                $data['NumPages'] = $numPages;
                $data['CurrentPage'] = 1;

                $page = $page > $numPages ? $numPages : $page; // Page max?
                $data['CurrentPage'] = $page;

                $images = $galleryManager->getGallery($user->getId(), $page);
               
                foreach ($images as $key => $value) 
                { // Compact numbers
                    $images[$key]->setComments($this->shortNumber($images[$key]->getComments()));
                    $images[$key]->setLikes($this->shortNumber($images[$key]->getLikes()));
                    $images[$key]->setViews($this->shortNumber($images[$key]->getViews()));
                }

                if (!empty($images)) 
                { // User data found and ready
                    $data['Images'] = $images;
                }
                $data['User'] = $user;
                $data['Owner'] = $this->isUserPageOwner($user);
            }
        }        

        $this->set($data);
        $this->render("gallery", true);
    }

    public function gallerySingle(...$ids)
    { // AJAX    

        $data = [];

        if (!empty($ids[0]))
        { // Image ID parameter went trough
          // Secure url input
            $imgId = (int) $ids[0];

            $userManager = new UserManager();
            $galleryManager = new GalleryManager();
            $image = $galleryManager->getImageContent($imgId);

            if (!empty($image)) 
            {
                $image = $image[0];

                // Add 1 view
                $galleryManager->addOne($image->getId());

                // Convert BBcode
                //$image->setImgContent($this->secure_input($image->getImgContent()));
                //$image->setImgContent($this->ParseBBCode($image->getImgContent()));

                // Compact numbers
                $image->setComments($this->shortNumber($image->getComments()));
                $image->setLikes($this->shortNumber($image->getLikes()));
                $image->setViews($this->shortNumber($image->getViews()));

                // Get author user data
                $author = $userManager->getUserInfoBy($image->getUserId(), 'id');
                
                if (!empty($author))
                { // Author data found
                    $data['Author'] = $author;
                    $data['Image'] = $image;                    

                    $commentManager = new CommentManager();

                    if (CONNECTED) 
                    { // Get user data if connected

                        $data['Owner'] = $this->isUserPageOwner($author);

                        $user = $userManager->getUserInfoBy($_SESSION['userId'], 'id');
                        if (!empty($user))
                        {
                            $data['User'] = $user;
                            $data['ImageLiked'] = $galleryManager->liked($image->getId(), $user->getId());
                            
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
                                { // If everything is good, post the image

                                    $formD['postId'] = $image->getId();
                                    $formD['userId'] = $user->getId();
                                    $formD['userName'] = $user->getUserName();
                                    $comment = new Comment($formD);

                                    if ($commentManager->createComment($comment, DB_COMMENTS_IMG))
                                    { // Should succeed if userId valid
                                        $galleryManager->addOne($image->getId(), 'comments');
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

                    $comments = $commentManager->getComments($imgId, $userId, DB_COMMENTS_IMG);
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
        $this->render("gallerySingle", true);
    }

    public function likeImage(...$ids)
    { // AJAX
        if (CONNECTED) 
        { // Check if connected and if the id correspond to the session variable
            if (!empty($ids[0]) && !empty($ids[1]))
            {
                // Secure data passed
                $imgId = (int) $ids[0]; // imgId
                $userId = (int) $ids[1]; // userId

                if ($imgId > 0 && $userId > 0) {
                    if ($_SESSION['userId'] === $userId)
                    { // Making sure the connected user correspond to the id in session
                        $galleryManager = new GalleryManager();
                        if ($galleryManager->likeImage($imgId, $userId))
                        { // Add 1 like on the post, done in likeImage method
                            //$galleryManager->addOne($imgId, 'likes');
                        }
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
            $imgId = (int) $ids[0]; // imgId
            $userId = (int) $ids[1]; // userId
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'id');

            if ($this->isUserPageOwner($user)) 
            { // Making sure the connected user is the owner
                $galleryManager = new GalleryManager();
                $image = $galleryManager->getImageContent($imgId);
                if (!empty($image[0])) {
                    if ($galleryManager->deleteImage($imgId))
                    { // Delete images linked to this post
                        $target_file = ROOT . "assets/images/gallery/" . $image[0]->getImgPath();
                        $thumb_file = ROOT . "assets/images/gallery/" . $image[0]->getImgThumbnail();
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        if (file_exists($thumb_file)) {
                            unlink($thumb_file);
                        }
                    }
                }
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
                if ($commentManager->likeComment($comId, $postId, $userId, DB_COMMENTS_IMG)) {
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
                if ($commentManager->deleteComment($comId, $postId, $userId, DB_COMMENTS_IMG)) {
                    $this->addOne($postId, 'comments', '-');
                }
            }
        }    
    }
    /////

}