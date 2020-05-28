<?php 
if (!CONNECTED) {
?>

<h2 class="text-center">S'enregistrer</h2>
<p class="text-center">
<?php

/*
<small id="passwordHelpBlock" class="form-text text-muted"> 
Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
</small>
*/

// Boolean to check if an error is existing
$nameError = !empty($Errors['nameLen']) || !empty($Errors['nameEmpty']) || !empty($Errors['nameExisting']);
$passError = !empty($Errors['passLen']) || !empty($Errors['passEmpty']);
$passVerifyError = !empty($Errors['passVerify']) || !empty($Errors['passVerifyEmpty']) ;
$mailError = !empty($Errors['mailEmpty']) || !empty($Errors['emailExisting']);
?>
</p>

<form method='post' action=''>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
            </span>
        </div>
        <input type="text" class="form-control <?php echo $nameError ? 'is-invalid' : (!empty($Data['userName']) ? 'is-valid' : '') ?>" 
        name="uName" id="uName" <?php echo !empty($Data['userName']) ? 'value="' . $Data['userName'] . '"' : 'placeholder="Nom"' ?>>
        <?php echo !empty($Errors['nameLen']) ? '<div class="invalid-feedback">' . $Errors['nameLen'] . '</div>' : '' ?>
        <?php echo !empty($Errors['nameEmpty']) ? '<div class="invalid-feedback">' . $Errors['nameEmpty'] . '</div>' : '' ?>
        <?php echo !empty($Errors['nameExisting']) ? '<div class="invalid-feedback">' . $Errors['nameExisting'] . '</div>' : '' ?>
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
        name="password" id="password" <?php echo !empty($Data['userPassword']) ? 'value="' . $Data['userPassword'] . '"' : 'placeholder="Mot de passe"' ?>>
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
        name="password_verify" id="password_verify" <?php echo !empty($Data['password_verify']) ? 'value="' . $Data['password_verify'] . '"' : 'placeholder="Mot de passe - vérification"' ?>>
        <?php echo !empty($Errors['passVerifyEmpty']) ? '<div class="invalid-feedback">' . $Errors['passVerifyEmpty'] . '</div>' : '' ?>
        <?php echo !empty($Errors['passVerify']) ? '<div class="invalid-feedback">' . $Errors['passVerify'] . '</div>' : '' ?>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <svg class="bi bi-envelope-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M.05 3.555L8 8.414l7.95-4.859A2 2 0 0014 2H2A2 2 0 00.05 3.555zM16 4.697l-5.875 3.59L16 11.743V4.697zm-.168 8.108L9.157 8.879 8 9.586l-1.157-.707-6.675 3.926A2 2 0 002 14h12a2 2 0 001.832-1.195zM0 11.743l5.875-3.456L0 4.697v7.046z"/>
                </svg>
            </span>
        </div>
        <input type="email" 
        class="form-control <?php echo $mailError ? 'is-invalid' : (!empty($Data['userMail']) ? 'is-valid' : '') ?>" 
        name="uMail" id="uMail" <?php echo !empty($Data['userMail']) ? 'value="' . $Data['userMail'] . '"' : 'placeholder="E-mail"' ?>>
        <?php echo !empty($Errors['mailEmpty']) ? '<div class="invalid-feedback">' . $Errors['mailEmpty'] . '</div>' : '' ?>
        <?php echo !empty($Errors['emailExisting']) ? '<div class="invalid-feedback">' . $Errors['emailExisting'] . '</div>' : '' ?>
    </div>
   
    <button type="submit" class="btn btn-primary ">Valider</button>
</form>

<?php 
}
?>