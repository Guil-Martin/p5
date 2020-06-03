<div class="container-fluid" >

<?php 

$commentContentError = !empty($Errors['commentContentEmpty']) || !empty($Errors['commentContentLen']);

if (!empty($News)) {
?>

    <div class = "row mt-3">
        <div class = "col-md">
            <h2 class="text-center"><p><?php echo $News->getNewsTitle() ?></p></h2>
        </div>
    </div>
    <div class="row border-bottom border-light">
        <div class="col-md">
            <p><?php echo $News->getNewsContent() ?></p>

            <?php
            if (empty($Owner) && !empty($User)) {
            ?>          
            <div class="mb-3 d-flex">   
                <button class="like ml-auto btn <?php echo !empty($NewsLiked) ? 'btn-danger' : 'btn-secondary' ?>" cont="<?php echo WEBROOT . 'news/likeNews/' . $News->getId() . '/' . $User->getId() ?>">
                    <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                    </svg>
                    J'aime
                </button>
            </div> 
                
            <?php
            }            
            ?>
        </div>
    </div>
    <div class="row border-bottom border-light">
        <div class="col-md">

            <?php
            if (empty($Owner) && !empty($User)) {?>

                <div class="m-2"><strong><?php echo $User->getUserName() ?></strong></div>

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
            
            <?php } elseif (empty($Owner)) { ?>

                <p class="text-center mt-3">Veuillez vous connecter si vous souhaitez poster un message.</p>

                <div class="row mb-3 mt-3">

                        <button class="btn btn-secondary col-md-3 offset-md-3" href="<?php echo WEBROOT . 'users/register' ?>">
                            <svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                            </svg>
                            S'enregistrer
                        </button>


                        <button class="btn btn-secondary col-md-3" href="<?php echo WEBROOT . 'users/login' ?>">
                            <svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                            </svg>
                            Connexion
                        </button>
                
                </div>
            
            <?php } ?>

        </div>
    </div>


            
    <?php
    if (!empty($Comments)) {
        foreach ($Comments as $com) {
        /*
            <a href="<?php echo WEBROOT . 'users/page/' . echo $com->setContentId() ?>" class="">
            </a>
        */
    ?>

        <div class="row">
            <div class="col-md">
            <div class="ml-2 mt-2"><strong><?php echo $com->getUserName() ?></strong></div>           
            <div class="ml-2 mb-2"><small><?php echo $com->getDatePosted() ?></small></div>
            <div class="border border-light m-2 p-2"><?php echo $com->getCommentContent() ?></div>

            </div>
        </div>

    <?php
        }
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