<h2 class="text-center">Connexion</h2>
<p class="text-center">
<?php
$nameError = !empty($Errors['nameLen']) || !empty($Errors['nameEmpty']) || !empty($Errors['nameExisting']);
$passError = !empty($Errors['passLen']) || !empty($Errors['passEmpty']) || !empty($Errors['passWrong']);

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
        <?php echo !empty($Errors['passWrong']) ? '<div class="invalid-feedback">' . $Errors['passWrong'] . '</div>' : '' ?>
    </div>
   
    <button type="submit" class="btn btn-primary ">Valider</button>
</form>