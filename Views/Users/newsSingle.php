<div class="container-fluid" >

<?php 

$commentContentError = !empty($Errors['commentContentEmpty']) || !empty($Errors['commentContentLen']);

if (!empty($News)) {
?>

    <div class = "row pt-3">
        <div class = "col-md">
            <h2 class="text-center"><p><?php echo $News->getNewsTitle() ?></p></h2>
        </div>
    </div>
    <div class = "row">
        <div class = "col-md">
            <p>______________________</p>
            <p><?php echo $News->getNewsContent() ?></p>
            <p>______________________</p>
        </div>
    </div>
    <div class = "row">
        <div class = "col-md">

            <?php
            if (empty($Owner) && !empty($User)) {?>

                <p><?php echo $User->getUserName() ?></p>

                <form id="validateForm" action="" cont="<?php echo WEBROOT . 'users/newsSingle/' . $News->getId() . '/' . $User->getId() ?>">

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
                        <textarea rows='10'
                        class="form-control <?php echo $commentContentError ? 'is-invalid' : (!empty($Data['msgContent']) ? 'is-valid' : '') ?>"
                        name="msgContent" id="msgContent" placeholder="Contenu de votre message - limite 500 charactères"><?php echo !empty($Data['msgContent']) ? $Data['msgContent'] : '' ?></textarea>
                        <?php echo !empty($Errors['commentContentEmpty']) ? '<div class="invalid-feedback">' . $Errors['commentContentEmpty'] . '</div>' : '' ?>
                        <?php echo !empty($Errors['commentContentLen']) ? '<div class="invalid-feedback">' . $Errors['commentContentLen'] . '</div>' : '' ?>
                    </div>
                
                    <div class="text-center">
                        <button id="submit" type="submit" class="btn btn-primary submit">Valider</button>
                    </div>
                </form>
            
            <?php } elseif (empty($Owner)) { ?>

                <p>Veuillez vous connecter si vous souhaitez poster un message.</p>

                <button class="btn btn-secondary">
                <a class="nav-link" href="<?php echo WEBROOT . 'users/register' ?>">
                    <svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                    </svg>
                    S'enregistrer
                </a>
                </button>

                <button class="btn btn-secondary">
                <a class="nav-link" href="<?php echo WEBROOT . 'users/login' ?>">
                    <svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                    </svg>
                    Connexion
                </a>
                </button>
            
            <?php } ?>

        </div>
    </div>

    <p>______________________</p>

    <div class = "row">
        <div class = "col-md">
            
            <?php
            if (!empty($Comments)) {
                foreach ($Comments as $com) {
            ?>
                    <p><?php echo $com->getUserName() ?></p>
                    <p><?php echo $com->getCommentContent() ?></p>
                    <p>______________________</p>
            <?php
                }
            } else {
            ?>
                <p>Pas de messages.</p>
            <?php
            }
            ?>

        </div>
    </div>

<?php
}
?>

</div>