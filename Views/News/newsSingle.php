<div class="container-fluid" >

<?php 

$commentContentError = !empty($Errors['commentContentEmpty']) || !empty($Errors['commentContentLen']);

if (!empty($News)) {
?>

    <div class = "row mt-3 border-bottom border-light">
        <div class = "col">
            <h2 class="text-center"><p><?php echo $News->getNewsTitle() ?></p></h2>
        </div>
    </div>

    <div class = "row border-bottom border-light">
        <div class = "col text-center mt-3 mb-3">
            <div class="">
                <img width="25" height="25" src="<?php echo WEBROOT . 'assets/images/users/' . $Author->getAvatar() ?>" alt="<?php echo $Author->getUserName() ?>" class="img-fluid">
                <small>Posté par : <a href="<?php echo WEBROOT . 'users/page/' . $Author->getContentId() ?>"><?php echo $Author->getUserName() ?></a></small>
            </div>            
            <small class="">Posté le <?php echo date("d/m/Y à H:i", strtotime($News->getDatePosted())) ?></small>
        </div>
    </div>    

    <div class="row border-bottom border-light">
        <div class="col">
            <div class="mt-3 mb-3"><?php echo $News->getNewsContent() ?></div>


            <div class="mb-3 d-flex">

                <div class="h-25 ml-auto">
                    
                    <div class="">
                        <span>
                            <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                            </svg>
                        </span>
                        <span class="pr-2 pl-1"><?php echo $News->getLikes() ?></span>
                    

                    <?php
                    if (empty($Owner) && !empty($User)) {
                    ?>
                    <button class="like btn btn-sm <?php echo !empty($NewsLiked) ? 'btn-danger' : 'btn-secondary' ?>" cont="<?php echo WEBROOT . 'news/likeNews/' . $News->getId() . '/' . $User->getId() ?>">
                        <span>J'aime</span>
                    </button>
                    <?php
                    }
                    ?>

                    </div>

                </div>
                
            </div> 
                

        </div>
    </div>
    <div class="row border-bottom border-light">
        <div class="col-md">

            <?php
            if (empty($Owner) && !empty($User)) {?>

                <div class="userName mt-2 mb-2">
                    <img width="25px" height="25px" src="<?php echo WEBROOT . 'assets/images/users/' . $User->getAvatar() ?>" alt="<?php echo $User->getUserName() ?>" class="img-fluid">
                    <a class="pl-2" href="<?php echo WEBROOT . 'users/page/' . $User->getContentId() ?>" class="href">
                    <?php echo $User->getUserName() ?>
                    </a>
                </div> 

                <button class="btn btn-light btn-sm mb-2" type="button" data-toggle="collapse" data-target="#comForm" aria-expanded="false" aria-controls="comForm">
                <svg class="bi bi-chat-square-dots" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h2.5a2 2 0 0 1 1.6.8L8 14.333 9.9 11.8a2 2 0 0 1 1.6-.8H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                    <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
                <strong class="pl-2 pr-2">Commenter</strong>
                <svg class="bi bi-chevron-down" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                </svg>
                </button>
                
                <div class="collapse" id="comForm">
                    <div class="row">                    
                        <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">

                        <form id="validateForm" action="" cont="<?php echo WEBROOT . 'news/newsSingle/' . $News->getId() . '/' . $User->getId() ?>">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <svg class="bi bi-file-earmark-text" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                                            <path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/>
                                            <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                        </svg>
                                    </span>
                                </div>
                                <textarea rows='6'
                                class="form-control <?php echo $commentContentError ? 'is-invalid' : (!empty($Data['msgContent']) ? 'is-valid' : '') ?>"
                                name="msgContent" id="msgContent" placeholder="Contenu de votre message - limite 500 caractères"><?php echo !empty($Data['msgContent']) ? $Data['msgContent'] : '' ?></textarea>
                                <?php echo !empty($Errors['commentContentEmpty']) ? '<div class="invalid-feedback">' . $Errors['commentContentEmpty'] . '</div>' : '' ?>
                                <?php echo !empty($Errors['commentContentLen']) ? '<div class="invalid-feedback">' . $Errors['commentContentLen'] . '</div>' : '' ?>
                            </div>
                            <div class="text-center mb-3">
                                <button id="submit" type="submit" class="btn btn-secondary submit">Valider</button>
                            </div>
                        </form>

                        </div>                    
                    </div>
                </diV>
            
            <?php } elseif (empty($Owner)) { ?>

                <p class="text-center mt-3">Veuillez vous connecter si vous souhaitez poster un message.</p>

                <div class="row mb-3 mt-3">

                    <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">

                        <div class="row pl-4 pr-4">

                            <a class="col-sm btn btn-secondary" href="<?php echo WEBROOT . 'users/register' ?>">
                                <svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                                </svg>
                                S'enregistrer
                            </a>

                            <a class="col-sm btn btn-secondary" href="<?php echo WEBROOT . 'users/login' ?>">
                                <svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                                </svg>
                                Connexion
                            </a>

                        </div>
                        
                    </div>
                
                </div>
            
            <?php } ?>

        </div>
    </div>


            
    <?php
    if (!empty($Comments)) {
        foreach ($Comments as $com) {
    ?>

        <div class="row">
            <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">   
                
                <div class="commentBox border border-light bg-dark p-2 mt-2">

                    <div class="userName">
                        <img width="25px" height="25px" src="<?php echo WEBROOT . 'assets/images/users/' . $com["avatar"] ?>" alt="<?php echo $com["userName"] ?>" class="img-fluid">
                        <a class="pl-2" href="<?php echo WEBROOT . 'users/page/' . $com['contentId'] ?>" class="href">
                        <?php echo $com["userName"] ?>
                        </a>
                        <div class=""><small>Posté le <?php echo date("d/m/Y à H:i", strtotime($com['datePosted'])) ?></small></div>
                    </div>                 

                    <div class="pl-2 mt-2 commentContent"><?php echo $com['commentContent'] ?></div>

                    <div class="d-flex mt-2">
                        <div class="ml-auto">
                            <div class="buttons">

                                <div class="" >
                                    <span>
                                        <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                    </span>
                                    <span class="pr-2 pl-1"><?php echo $com['likes'] ?></span>
                                

                                <?php
                                if (!empty($User)) {
                                ?>
                                <button class="like btn btn-sm <?php echo !empty($com['likedComment']) ? 'btn-danger' : 'btn-secondary' ?>" cont="<?php echo WEBROOT . 'news/likeComment/' . $com['id'] . '/' . $News->getId() . '/' . $User->getId() ?>">
                                    J'aime
                                </button>
                                <?php
                                }
                                ?>

                                </div>

                            </div>
                        </div>
                    </div>

                    <?php
                    if (!empty($User) && $User->getId() == $com['userId']) {

                    ?>
                    <div class="sup">
                        <button class="delCom btn btn-danger" aria-label="Supprimer" title="Supprimer" cont="<?php echo WEBROOT . "news/delComment/" . $com["id"] . "/" . $News->getId() . '/' . $User->getId() ?>">
                            <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </button>
                    </div>
                    <?php
                    }
                    ?>


            
                </div>

            </div>
        </div>

        <?php
        }
        ?>

        <div class="d-flex justify-content-center">
            <button class="btn btn-dark m-2" cont="<?php ?>">
            Plus de commentaires
            </button>
        </div>

        <?php
    } else {
    ?>
        <p class="text-center mt-4">Pas de messages.</p>
    <?php
    }
    ?>



<?php
}
?>

</div>