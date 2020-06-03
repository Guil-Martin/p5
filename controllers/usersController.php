<?php

class usersController extends Controller
{

    function register()
    { // treat register form 

        $d = [];

        if (isset($_POST['uName']))
        {
            require_once(ROOT . 'Models/UserManager.php');
            $userManager = new UserManager();

            $data = [];

            // change names for corresponding database and methods names 
            $data['userName'] = $_POST['uName'];
            $data['userMail'] = $_POST['uMail'];
            $data['userPassword'] = $_POST['password'];
            $data['password_verify'] = $_POST['password_verify'];

            $data = $this->secure_form($data);

            $errors = [];

            if (empty($data['userName']))
            { // username is empty
                $errors['nameEmpty'] = 'Veuillez renseigner un nom';
            }
            else 
            { // At least one char has been entered
                $nameLenght = strlen($data['userName']);             
                if ($nameLenght < 5)
                { // username is too short, at least 5 letters
                    $errors['nameLen'] = 'Le nom choisi est trop court, au moins 5 lettres sont requises.'; 
                } elseif ($nameLenght > 30)
                { // username too long, less than 30 characters
                    $errors['nameLen'] = 'Le nom choisi est trop long, moins de 30 caractères sont requis.'; 
                } 
                else 
                { // Username is ok
                    if ($userManager->existing($data['userName']))
                    { // Check if username already registered
                        $errors['nameExisting'] = 'Le nom d\'utilisateur existe déjà.';
                    }
                }
            }

            if (empty($data['userPassword']))
            { // Password empty
                $errors['passEmpty'] = 'Veuillez renseigner un mot de passe.';
            } 
            else 
            { // At least one char has been entered
                $passLenght = strlen($data['userPassword']);
                if ($passLenght < 5)
                { // Password is too short, at least 5 characters
                    $errors['passLen'] = 'Le mot de passe choisi est trop court, au moins 5 caractères sont requis.'; 
                } elseif ($passLenght > 30)
                { // Password too long, less than 30 characters
                    $errors['passLen'] = 'Le mot de passe choisi est trop long, moins de 30 caractères sont requis.'; 
                }
            }

            if (empty($data['password_verify']))
            { // password verify empty
                $errors['passVerifyEmpty'] = 'Veuillez renseigner à nouveau le mot de passe.';
            }
            else
            { // At least one char has been entered
                if ($data['userPassword'] !== $data['password_verify']) 
                { // password and password verify are not the same
                    $errors['passVerify'] = 'Le mot de passe et la vérification ne sont pas similaires.';
                }
            }

            if (empty($data['userMail'])) 
            { // Email is empty
                $errors['mailEmpty'] = 'Veuillez renseigner une adresse Email valide.';
            } 
            else 
            {
                if (!filter_var($data['userMail'], FILTER_VALIDATE_EMAIL) || strlen($data['userMail']) > 50) 
                { // Email is not valid
                    $errors[] = 'L\'Email n\'est pas valide.';
                } elseif ($userManager->existing($data['userMail'], 'userMail'))
                { // Check if email already registered
                    $errors['emailExisting'] = 'Cet E-mail est déjà enregistré.';
                }
            }

            if (empty($errors)) 
            { // No errors, register the user by sending data to the model
              // and redirect to its index page
                require_once(ROOT . 'Models/User.php');

                // Hasing password
                $data['userPassword'] = password_hash($data['userPassword'], PASSWORD_DEFAULT);

                // Set up a user object with existing data
                $user = new User($data);
                
                // Set hashed password and content ID
                $user->setUserPassword($data['userPassword']);

                $urlValidName = $this->removeAccents($data['userName']);
                $urlValidName = $this->urlValidId($urlValidName);
                $user->setContentId($this->urlValidId($urlValidName));

                if ($userManager->registerUser($user))
                { // the user has successfully been registered in the DB

                    // TODO log the user in before redirecting them on their user page

                    header("Location: " . WEBROOT . "users/page/" . $user->getContentId());
                    return;
                }
                else
                {
                    
                    // couldn't register the user in the database
                    return;
                }
            }

            $d['Data'] = $data;
            $d['Errors'] = $errors;
        }
        
        //var_dump($d);

        $this->set($d);
        $this->render("register");
    }

    function login()
    { // Treat login form

        $d = [];

        if (isset($_POST["uName"]))
        {
            require_once(ROOT . 'Models/UserManager.php');

            // anti session fixation
            session_regenerate_id();

            $userManager = new UserManager();
            $data = [];

            // change names for corresponding database and methods names 
            $data['userName'] = $_POST['uName'];
            $data['userPassword'] = $_POST['password'];

            $data = $this->secure_form($data);

            $errors = [];

            if (empty($data['userName']))
            { // username is empty
                $errors['nameEmpty'] = 'Veuillez renseigner un nom';
            } 
            else 
            {
                $nameLenght = strlen($data['userName']);
                if ($nameLenght < 5)
                { // username is too short, at least 5 letters
                    $errors['nameLen'] = 'Le nom choisi est trop court, au moins 5 lettres sont requises.';
                } elseif ($nameLenght > 30)
                { // username too long, less than 30 characters
                    $errors['nameLen'] = 'Le nom choisi est trop long, moins de 30 caractères sont requis.'; 
                } 
            }


            if (empty($data['userPassword']))
            { // Password empty
                $errors['passEmpty'] = 'Veuillez renseigner un mot de passe.';
            } 
            else 
            { // At least one char has been entered
                $passLenght = strlen($data['userPassword']);
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
                require_once(ROOT . 'Models/User.php');
                $user = $userManager->getUserInfoBy($data['userName']);

                if (!empty($user))
                { // getLoginInfo returns a valid User object meaning the user exists
                    if (!password_verify($data['userPassword'], $user->getUserPassword())) 
                    { // adds an error in not valid
                        $errors['passWrong'] = 'Mot de passe non valide';
                    }
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



            $d['Data'] = $data;
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
        require_once(ROOT . 'Models/UserManager.php');
        require_once(ROOT . 'Models/User.php');

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
                $d['Owner'] = $this->isUserPageOwner($user->getContentId());
            }
            
        }

        $this->set($d);
        $this->render("page");
    }

}