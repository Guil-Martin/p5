<?php 
if (!empty($Owner)) {
?>

<p class="text-center">
<?php

/*
<small id="passwordHelpBlock" class="form-text text-muted"> 
Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
</small>
*/

// Boolean to check if an error is existing

$passCurrentError = !empty($Errors['passWrong']);
$passError = !empty($Errors['passLen']) || !empty($Errors['passEmpty']);
$passVerifyError = !empty($Errors['passVerify']) || !empty($Errors['passVerifyEmpty']);
$avatarError = !empty($Errors['avatarFormat']);
$bioError = !empty($Errors['bioContent']);


?>
</p>

<h2 class="text-center"><?php echo $User->getUserName() ?></h2>

<?php 
if (!empty($Success))
{ ?>
    <p class="text-center text-success">Profile édité avec succès</p>
    <div class="text-center mb-3">
        <button cont="Profil" class="postSuccess btn btn-primary submit">Retour à la page de membre</button>
    </div>
<?php
}
else 
{
?>

<form enctype="multipart/form-data" id="validateForm" class="" method="post" action="" cont="<?php echo WEBROOT . 'users/profileEdit/' . $User->getId() ?>">


    <div class="text-center mt-3">
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#mod_mdp" aria-expanded="false" aria-controls="mod_mdp">
        <strong>Changer de mot de passe</strong>
        <svg class="bi bi-chevron-down" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
        </svg>
        </button>
    </div>
    <div class="collapse" id="mod_mdp">
        <div class="row">
            <div class="pt-4 mx-auto col-12 col-sm-6">

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <svg class="bi bi-lock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <rect width="11" height="9" x="2.5" y="7" rx="2"/>
                                <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                    <input type="password" 
                    class="form-control <?php echo $passCurrentError ? 'is-invalid' : (!empty($Data['passCurrent']) ? 'is-valid' : '') ?>" 
                    name="passCurrent" id="passCurrent" <?php echo !empty($Data['passCurrent']) ? 'value="' . $Data['passCurrent'] . '"' : 'placeholder="Mot de passe actuel"' ?>>
                    <?php echo !empty($Errors['passWrong']) ? '<div class="invalid-feedback">' . $Errors['passWrong'] . '</div>' : '' ?>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <svg class="bi bi-lock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <rect width="11" height="9" x="2.5" y="7" rx="2"/>
                                <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                    <input type="password" 
                    class="form-control <?php echo $passError ? 'is-invalid' : (!empty($Data['userPassword']) ? 'is-valid' : '') ?>" 
                    name="password" id="password" <?php echo !empty($Data['userPassword']) ? 'value="' . $Data['userPassword'] . '"' : 'placeholder="Nouveau mot de passe"' ?>>
                    <?php echo !empty($Errors['passEmpty']) ? '<div class="invalid-feedback">' . $Errors['passEmpty'] . '</div>' : '' ?>
                    <?php echo !empty($Errors['passLen']) ? '<div class="invalid-feedback">' . $Errors['passLen'] . '</div>' : '' ?>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <svg class="bi bi-lock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <rect width="11" height="9" x="2.5" y="7" rx="2"/>
                                <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                    <input type="password" 
                    class="form-control <?php echo $passVerifyError ? 'is-invalid' : (!empty($Data['password_verify']) ? 'is-valid' : '') ?>" 
                    name="password_verify" id="password_verify" <?php echo !empty($Data['password_verify']) ? 'value="' . $Data['password_verify'] . '"' : 'placeholder="Nouveau mot de passe - vérification"' ?>>
                    <?php echo !empty($Errors['passVerifyEmpty']) ? '<div class="invalid-feedback">' . $Errors['passVerifyEmpty'] . '</div>' : '' ?>
                    <?php echo !empty($Errors['passVerify']) ? '<div class="invalid-feedback">' . $Errors['passVerify'] . '</div>' : '' ?>
                </div>

            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#mod_avatar" aria-expanded="false" aria-controls="mod_avatar">
        <strong>Changer l'avatar</strong>
        <svg class="bi bi-chevron-down" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
        </svg>
        </button>
    </div>
    <div class="collapse" id="mod_avatar">

        <div class="pt-4">
            <div class="mx-auto avatarPreview">
                <img src="<?php echo WEBROOT . 'assets/images/users/' . $User->getAvatar() ?>" alt="<?php $User->getUserName() ?>" class="img-fluid">
            </div>

            <div class="form-group mx-auto fileUpload">
                <label for="fileUpload" aria-label="Avatar" title="Avatar"><strong>Avatar</strong> (optionnel) 100px par 100px, l'image sera redimentionée si plus large</label>
                <input type="file" name="fileUpload" class="form-control-file <?php echo $avatarError ? 'is-invalid' : '' ?>">
                <?php echo !empty($Errors['avatarFormat']) ? '<div class="invalid-feedback">' . $Errors['avatarFormat'] . '</div>' : '' ?>
            </div>
        </div>

    </div>   

    <div class="text-center mt-3">
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#mod_bio" aria-expanded="false" aria-controls="mod_bio">
        <strong>Changer la bio</strong>
        <svg class="bi bi-chevron-down" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
        </svg>
        </button>
    </div>

    <div class="collapse" id="mod_bio">

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
            <textarea rows='20'
            class="form-control <?php echo $bioError ? 'is-invalid' : (!empty($Data['bio']) ? 'is-valid' : '') ?>"
            name="postContent" id="postContent" placeholder="Contenu de la nouvelle"><?php echo !empty($Data['bio']) ? $Data['bio'] : '' ?></textarea>
            <?php echo !empty($Errors['bioContent']) ? '<div class="invalid-feedback">' . $Errors['bioContent'] . '</div>' : '' ?>
        </div>

    </div>

    <div class="text-center mt-3 mb-3">
        <button type="submit" class="btn btn-primary ">Modifier</button>
    </div>
</form>

<?php 
}
}
?>