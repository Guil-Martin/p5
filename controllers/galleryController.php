<?php

class galleryController extends Controller
{

    function galleryCreate(...$data)
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

                $formD['imgTitle'] = $_POST['title'];
                $formD['imgContent'] = $_POST['content'];

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

                //var_dump($_POST);
                var_dump($_FILES);

                //// IMAGE
                require_once(ROOT . 'Models/SimpleImage.php');
                if (empty($_FILES['image']['name'])) {
                    $errors['imageEmpty'] = 'Veuillez séléctionner une image à uploader'; 
                }
                else
                {
                    $file = $_FILES["image"]["tmp_name"][0];
                    $name = basename($_FILES["image"]["name"][0]); // basename() may prevent filesystem traversal attacks;
                    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    
                    if (!in_array($extension, array('jpg', 'png', 'jpeg', 'gif')))
                    { // Allow only some formats
                        $errors['imageFormat'] = "Image - Seuls les formats JPG, JPEG, PNG & GIF sont autorisés."; 
                    }
                }
                ////

                if (empty($errors)) 
                { // If everything is good, post image
                    require_once(ROOT . 'Models/GalleryManager.php');
                    require_once(ROOT . 'Models/UserManager.php');
                    require_once(ROOT . 'Models/Image.php');                    
                    require_once(ROOT . 'Models/User.php');

                    $userManager = new UserManager();
                    $user = $userManager->getUserInfoBy($data[0], 'Id');

                    if (!empty($user))
                    {
                        if (!empty($file))
                        { //// IMAGE
                            $simpleImage = new SimpleImage();
                            // Creates user folder if doesn't exist
                            $target_folder = ROOT . "images/gallery/" . $user->getContentId();
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
                           
                            $simpleImage->load($file);
                            if ($simpleImage->getWidth() > 300) {
                                $simpleImage->resizeToWidth(300);
                            }
                            if ($simpleImage->getHeight() > 300) {
                                $simpleImage->resizeToHeight(300);
                            }
                            
                            $simpleImage->save($thumb_file);
                            $formD['ImgThumbnail'] = $user->getContentId() . '/thumbnails/' . $rename;

                            // Main image
                            $target_file = $target_folder . '/' . $rename;

                            $simpleImage->load($file);
                            if ($simpleImage->getWidth() > 1920) {
                                $simpleImage->resizeToWidth(1920);
                            }
                            if ($simpleImage->getHeight() > 1080) {
                                $simpleImage->resizeToHeight(1080);
                            }

                            $simpleImage->save($target_file);
                            $formD['imgPath'] = $user->getContentId() . '/' . $rename;
                        }

                        $galleryManager = new GalleryManager();

                        // Fill missing info about the user, author of this image
                        $formD['userId'] = $user->getId();
                        $formD['author'] = $user->getUserName();

                        // Creates an image object to add it to the db
                        $image = new Image($formD);

                        var_dump($image);

                        if ($galleryManager->galleryCreate($image))
                        { // Should succeed if userId valid
                            $d['Success'] = 'Image postée avec succès';
                        }
                        else
                        { // Creation failed, delete uploaded image
                            if (!empty($file))
                            {
                                unlink($target_file);
                                unlink($thumb_file);
                            }
                        }
                    }

                }

                $d['Data'] = $formD;
                $d['Errors'] = $errors;

                //var_dump($d);

            }
        }
        
        $this->set($d);
        $this->render("galleryCreate", true);
    }

    function gallery(...$data)
    { // AJAX
        require_once(ROOT . 'Models/UserManager.php');
        require_once(ROOT . 'Models/GalleryManager.php');
        require_once(ROOT . 'Models/User.php');
        require_once(ROOT . 'Models/Image.php');

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
                $galleryManager = new GalleryManager();
                $images = $galleryManager->getGallery($user->getId());
                if (!empty($images)) 
                { // User data found and ready
                    $d['Images'] = $images;
                }
                $d['User'] = $user;
                $d['Owner'] = $this->isUserPageOwner($user->getContentId());
            }
        }

        $this->set($d);
        $this->render("gallery", true);
    }

    function gallerySingle(...$data)
    { // AJAX
        require_once(ROOT . 'Models/UserManager.php');
        require_once(ROOT . 'Models/User.php');
        require_once(ROOT . 'Models/Image.php');

        $d = [];

        /*
        if (!empty($data[0]))
        { // User ID parameter went trough
          // Secure url input
            $data[0] = (int) $data[0];

            // Get user data if found
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($data[0], 'Id');
  
            if (!empty($user))
            { // User data found and ready
                $images = $userManager->getImages($user->getId());
                if (!empty($images)) 
                { // User data found and ready
                    $d['Images'] = $images;
                }
                $d['User'] = $user;
                $d['Owner'] = $this->isUserPageOwner($user->getContentId());
            }
        }
        */

        $this->set($d);
        $this->render("imageSingle", true);
    }

}