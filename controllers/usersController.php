<?php

require_once(ROOT . 'Models/UserManager.php');
require_once(ROOT . 'Models/User.php');

class usersController extends Controller
{

    function register()
    { // treat register form 

        $d = [];

        if (isset($_POST['uName']))
        {            
            $userManager = new UserManager();

            $formD = [];
            $formD = $_POST;

            // change names for corresponding database and methods names 
            $formD['userName'] = $formD['uName'];
            $formD['userMail'] = $formD['uMail'];
            $formD['userPassword'] = $formD['password'];
            $formD['password_verify'] = $formD['password_verify'];

            $errors = [];

            if (empty($formD['userName']))
            { // username is empty
                $errors['nameEmpty'] = 'Veuillez renseigner un nom';
            }
            else 
            { // At least one char has been entered
                $nameLenght = strlen($formD['userName']);             
                if ($nameLenght < 5)
                { // username is too short, at least 5 letters
                    $errors['nameLen'] = 'Le nom choisi est trop court, au moins 5 lettres sont requises.'; 
                } elseif ($nameLenght > 30)
                { // username too long, less than 30 characters
                    $errors['nameLen'] = 'Le nom choisi est trop long, moins de 30 caractères sont requis.'; 
                } 
                else 
                { // Username is ok
                    if ($userManager->existing($formD['userName']))
                    { // Check if username already registered
                        $errors['nameExisting'] = 'Le nom d\'utilisateur existe déjà.';
                    }
                }
            }

            if (empty($formD['userPassword']))
            { // Password empty
                $errors['passEmpty'] = 'Veuillez renseigner un mot de passe.';
            } 
            else 
            { // At least one char has been entered
                $passLenght = strlen($formD['userPassword']);
                if ($passLenght < 5)
                { // Password is too short, at least 5 characters
                    $errors['passLen'] = 'Le mot de passe choisi est trop court, au moins 5 caractères sont requis.'; 
                } elseif ($passLenght > 30)
                { // Password too long, less than 30 characters
                    $errors['passLen'] = 'Le mot de passe choisi est trop long, moins de 30 caractères sont requis.'; 
                }
            }

            if (empty($formD['password_verify']))
            { // password verify empty
                $errors['passVerifyEmpty'] = 'Veuillez renseigner à nouveau le mot de passe.';
            }
            else
            { // At least one char has been entered
                if ($formD['userPassword'] !== $formD['password_verify']) 
                { // password and password verify are not the same
                    $errors['passVerify'] = 'Le mot de passe et la vérification ne sont pas similaires.';
                }
            }

            if (empty($formD['userMail'])) 
            { // Email is empty
                $errors['mailEmpty'] = 'Veuillez renseigner une adresse Email valide.';
            } 
            else 
            {
                if (!filter_var($formD['userMail'], FILTER_VALIDATE_EMAIL) || strlen($formD['userMail']) > 50) 
                { // Email is not valid
                    $errors[] = 'L\'Email n\'est pas valide.';
                } elseif ($userManager->existing($formD['userMail'], 'userMail'))
                { // Check if email already registered
                    $errors['emailExisting'] = 'Cet E-mail est déjà enregistré.';
                }
            }            
            
            if (empty($errors)) 
            { // No errors, register the user by sending data to the model
              // and redirect to its index page

                // Set default avatar
                $formD['avatar'] = "avatar/avatarDefault.png";

                // Hasing password
                $passBeforeHash = $formD['userPassword'];
                $formD['userPassword'] = password_hash($formD['userPassword'], PASSWORD_DEFAULT);

                // Set up a user object with existing data
                $user = new User($formD);
                
                // Set hashed password and content ID
                $user->setUserPassword($formD['userPassword']);

                $urlValidName = $this->removeAccents($formD['userName']);
                $urlValidName = $this->urlValidId($urlValidName);
                $user->setContentId($this->urlValidId($urlValidName));

                if ($userManager->registerUser($user))
                { // the user has successfully been registered in the DB
                    
                    // Connect user
                    $_SESSION['userName'] = $user->getUserName();
                    $_SESSION['userContentId'] = $user->getContentId();
                    $_SESSION['userId'] = $user->getId();

                    // redirect to personnal page
                    header("Location: " . WEBROOT . "users/page/" . $user->getContentId());
                    return;
                }
            }

            // secure before redisplay in form inputs
            if (!empty($passBeforeHash)) {
                $formD['userPassword'] = $passBeforeHash;
            }
            
            $formD = $this->secure_form($formD);

            $d['Data'] = $formD;
            $d['Errors'] = $errors;
        }

        $this->set($d);
        $this->render("register");
    }

    function login()
    { // Treat login form

        $d = [];

        if (isset($_POST["uName"]))
        {
            // anti session fixation
            session_regenerate_id();

            $userManager = new UserManager();
            $formD = [];

            // change names for corresponding database and methods names 
            $formD['userName'] = $_POST['uName'];
            $formD['userPassword'] = $_POST['password'];

            $errors = [];

            if (empty($formD['userName']))
            { // username is empty
                $errors['nameEmpty'] = 'Veuillez renseigner un nom';
            } 
            else 
            {
                $nameLenght = strlen($formD['userName']);
                if ($nameLenght < 5)
                { // username is too short, at least 5 letters
                    $errors['nameLen'] = 'Le nom choisi est trop court, au moins 5 lettres sont requises.';
                } elseif ($nameLenght > 30)
                { // username too long, less than 30 characters
                    $errors['nameLen'] = 'Le nom choisi est trop long, moins de 30 caractères sont requis.'; 
                } 
            }


            if (empty($formD['userPassword']))
            { // Password empty
                $errors['passEmpty'] = 'Veuillez renseigner un mot de passe.';
            } 
            else 
            { // At least one char has been entered
                $passLenght = strlen($formD['userPassword']);
                if ($passLenght < 5)
                { // Password is too short, at least 5 characters
                    $errors['passLen'] = 'Le mot de passe choisi est trop court, au moins 5 caractères sont requis.'; 
                } elseif ($passLenght > 30)
                { // Password too long, less than 30 characters
                    $errors['passLen'] = 'Le mot de passe choisi est trop long, moins de 30 caractères sont requis.'; 
                }
            }

            if (empty($errors)) 
            { // Check if the password is correct
                $user = $userManager->getUserInfoBy($formD['userName']);

                if (!empty($user))
                { // getLoginInfo returns a valid User object meaning the user exists
                    if (!password_verify($formD['userPassword'], $user->getUserPassword())) 
                    { // adds an error in not valid
                        $errors['passWrong'] = 'Mot de passe non valide';
                    }
                } 
                else 
                {
                    $errors['noUser'] = 'Cet utilisateur n\'existe pas.';
                }
            }

            if (empty($errors)) 
            { // If everything is good, saving data in session and redirect

                /* SETS A COOKIE WITH SALT
                $salt = "whatever1234";
                setcookie('password', md5($salt.$_POST['password']), time()+60*60*24*365,'/','www.mysite.org');
                */

                $_SESSION['userName'] = $user->getUserName();
                $_SESSION['userContentId'] = $user->getContentId();
                $_SESSION['userId'] = $user->getId();

                header("Location: " . WEBROOT . "users/page/" . $_SESSION['userContentId']);
                return;
            }

            // secure before redisplay in form inputs
            $formD = $this->secure_form($formD);

            $d['Data'] = $formD;
            $d['Errors'] = $errors;      
        }
        
        
        $this->set($d);
        $this->render("login");
    }

    function logout()
    {
        if (!empty($_SESSION['userName'])) unset($_SESSION['userName']);
        if (!empty($_SESSION['userContentId'])) unset($_SESSION['userContentId']);
        if (!empty($_SESSION['userId'])) unset($_SESSION['userId']);
        session_destroy();
        header("Location: " . WEBROOT);
    }
    
    function page(...$data)
    {
        $d = [];

        if (!empty($data[0])) 
        { // Content ID parameter went trough
          // Secure url input
            $data[0] = (string) $this->secure_input($data[0]);

            // Get user data if found
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($data[0], 'contentId');

            if (!empty($user))
            { // User data found and ready
                $d['User'] = $user;
                $d['Owner'] = $this->isUserPageOwner($user);
            }
            
        }

        $this->set($d);
        $this->render("page");
    }

    function profile(...$ids)
    { // AJAX
        $d = [];

        if (!empty($ids[0]))
        {
            $userId = (int) $ids[0];

            // Get user data if found
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'Id');

            if (!empty($user)) 
            {

                $d['User'] = $user;       
                
                $d['NumViewsImg'] = $userManager->getNum($user->getId(), 'views', DB_IMG);
                $d['NumViewsNews'] = $userManager->getNum($user->getId(), 'views', DB_NEWS);

                $d['NumLikesImg'] = $userManager->getNum($user->getId(), 'likes', DB_IMG);
                $d['NumLikesNews'] = $userManager->getNum($user->getId(), 'likes', DB_NEWS);

            }
        }

        $this->set($d);
        $this->render("profile", true);
    }

    function profileEdit(...$id)
    {

        $d = [];
        $errors = [];
        $formD = [];

        if (!empty($id[0])) 
        { // Content ID parameter went trough
          // Secure url input
            $userId = (int) $id[0];

            // Get user data if found
            $userManager = new UserManager();
            $user = $userManager->getUserInfoBy($userId, 'id');

            if (!empty($user)) 
            { // User data found and ready
                $d['User'] = $user;
                $d['Owner'] = $this->isUserPageOwner($user);

                $formD['bio'] = $user->getBio();

                //var_dump($formD);
                //var_dump($_POST);
                //var_dump($_FILES);
                
                if (!empty($_POST))
                {
                    $formD = $_POST;                    
                
                    //// Password change
                    if (!empty($formD['passCurrent']))
                    {
                        $edited = true;

                        $formD['userPassCurrent'] = $formD['passCurrent'];
                        $formD['userPassword'] = $formD['password'];
                        $formD['passVerify'] = $formD['password_verify'];  

                        if (!password_verify($formD['userPassCurrent'], $user->getUserPassword())) 
                        { // adds an error in not valid
                            $errors['passWrong'] = 'Mot de passe non valide';
                        }
                        else
                        {
                            if (empty($formD['userPassword']))
                            { // Password empty
                                $errors['passEmpty'] = 'Veuillez renseigner un mot de passe.';
                            } 
                            else 
                            { // At least one char has been entered
                                $passLenght = strlen($formD['userPassword']);
                                if ($passLenght < 5)
                                { // Password is too short, at least 5 characters
                                    $errors['passLen'] = 'Le mot de passe choisi est trop court, au moins 5 caractères sont requis.'; 
                                } elseif ($passLenght > 30)
                                { // Password too long, less than 30 characters
                                    $errors['passLen'] = 'Le mot de passe choisi est trop long, moins de 30 caractères sont requis.'; 
                                }
                            }
                            
                            if (empty($formD['passVerify']))
                            { // password verify empty
                                $errors['passVerifyEmpty'] = 'Veuillez renseigner à nouveau le mot de passe.';
                            }
                            else
                            { // At least one char has been entered
                                if ($formD['userPassword'] !== $formD['passVerify']) 
                                { // password and password verify are not the same
                                    $errors['passVerify'] = 'Le mot de passe et la vérification ne sont pas similaires.';
                                }
                            }
                            
                        }
                    }

                    if (!empty($formD['postContent']))
                    {
                        $edited = true;
                        $formD['bio'] = $formD['postContent'];
                    }

                }
                ////         
                //// AVATAR
                
                if (!empty($_FILES['fileUpload']['name'])) {

                    $edited = true;

                    $file = $_FILES["fileUpload"]["tmp_name"][0];
                    $name = basename($_FILES["fileUpload"]["name"][0]); // basename() may prevent filesystem traversal attacks;
                    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    
                    if (!in_array($extension, array('jpg', 'png', 'jpeg', 'gif')))
                    { // Allow only some formats
                        $errors['avatarFormat'] = "Avatar - Seuls les formats JPG, JPEG, PNG & GIF sont autorisés."; 
                    }
                    else
                    {
                        require_once(ROOT . 'Models/SimpleImage.php');                   
                        
                        // Creates user folder if doesn't exist
                        $target_folder = ROOT . "images/users/" . $user->getContentId() ;
                        if (!is_dir($target_folder))
                        { mkdir($target_folder); }
        
                        // Main folder
                        $mainAvatar = new SimpleImage();
                        $main_folder = $target_folder . '/avatar/';
                        if (!is_dir($main_folder))
                        { mkdir($main_folder); }
        
                        $mainAvatarName = $user->getContentId() . '.' . $extension;
                        $main_file = $main_folder . '/' . $mainAvatarName;
                        
                        $mainAvatar->load($file);
                        $mainAvatar->resize(100, 100);                  
        
                        $formD['avatar'] = $user->getContentId() . "/avatar/" . $mainAvatarName;
                        $user->setAvatar($formD['avatar']);

                    }
                }
                ////

                if (!empty($edited) && empty($errors)) {

                    if (!empty($formD['userPassCurrent'])) 
                    {
                        $formD['userPassword'] = password_hash($formD['userPassword'], PASSWORD_DEFAULT);
                        $user->setUserPassword($formD['userPassword']);
                    }

                    $user->setBio($formD['bio']);

                    if ($userManager->updateUser($user))
                    { // User data has been updated successfully

                        if (!empty($mainAvatar)) 
                        { // Save new avatar images on the server

                            $oldAvatar = ROOT . "images/users/" . $user->getAvatar();
                            if (file_exists($oldAvatar) && !strpos($user->getAvatar(), 'avatarDefault')) {
                                unlink($oldAvatar);
                            }
                            $mainAvatar->save($main_file);
                        }

                        // Will display the button to get back to the member page
                        $d['Success'] = 'Profile mis à jour avec succès';
                    }
                }

                $d['Data'] = $formD;
                $d['Errors'] = $errors;

            }
                
        }

        $this->set($d);
        $this->render("profileEdit", true);
        
    }

}