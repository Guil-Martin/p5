<h2 class="text-center">Modifier une image</h2>
<?php

if (!empty($Owner)) {



if (!empty($Data['dateEdited']))
{ ?>
    <p class="text-center">Dernière edition : <?php echo $Data['dateEdited'] ?></p>
<?php
} 
else
{
?>
    <p class="text-center">Pas encore édité</p>
<?php
}

// Boolean to check if an error is existing
$titleError = !empty($Errors['titleEmpty']) || !empty($Errors['titleLen']);
$imageError = !empty($Errors['imageFormat']);
$contentError = !empty($Errors['contentEmpty']) || !empty($Errors['contentLen']);

if (!empty($Success))
{ ?>
    <p class="text-center text-success">Image modifiée avec succès</p>
    <div class="text-center mb-3">
        <button cont="Gallery" class="postSuccess btn btn-primary submit">Retour à la page de membre</button>
    </div>
<?php
}
else 
{
?>

<form enctype="multipart/form-data" method="post" id="validateForm" class="mb-3" action="" cont="<?php echo WEBROOT . 'gallery/galleryEdit/' . $User->getId() . '/' . $Image->getId() ?>">

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <svg class="bi bi-file-earmark" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                    <path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/>
                </svg>
            </span>
        </div>
        <input type="text" class="form-control <?php echo $titleError ? 'is-invalid' : (!empty($Data['title']) ? 'is-valid' : '') ?>" 
        name="postTitle" id="postTitle" <?php echo !empty($Data['title']) ? 'value="' . $Data['title'] . '"' : 'placeholder="Titre de la nouvelle"' ?>>
        <?php echo !empty($Errors['titleEmpty']) ? '<div class="invalid-feedback">' . $Errors['titleEmpty'] . '</div>' : '' ?>
        <?php echo !empty($Errors['titleLen']) ? '<div class="invalid-feedback">' . $Errors['titleLen'] . '</div>' : '' ?>
    </div>


    <div class="mx-auto text-center" style="width: 150px; height: 150px;">
        <img style="max-height: 100%;" src="<?php echo WEBROOT . 'images/gallery/' . $Image->getImgPath() ?>" alt="<?php $User->getUserName() ?>" class="img-fluid">
    </div>

    <div class="form-group text-center mx-auto" style="width: 300px;">
        <label for="fileUpload" aria-label="Image" title="Image" class="<?php echo $imageError ? 'is-invalid' : '' ?>">Image a poster</label>
        <input type="file" id="image" name="fileUpload" class="form-control-file">
        <?php echo !empty($Errors['imageEmpty']) ? '<div class="invalid-feedback">' . $Errors['imageEmpty'] . '</div>' : '' ?>
        <?php echo !empty($Errors['imageFormat']) ? '<div class="invalid-feedback">' . $Errors['imageFormat'] . '</div>' : '' ?>
    </div>

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
        class="form-control <?php echo $contentError ? 'is-invalid' : (!empty($Data['content']) ? 'is-valid' : '') ?>"
        name="postContent" id="postContent" placeholder="Contenu de la nouvelle"><?php echo !empty($Data['content']) ? $Data['content'] : '' ?></textarea>
        <?php echo !empty($Errors['contentEmpty']) ? '<div class="invalid-feedback">' . $Errors['contentEmpty'] . '</div>' : '' ?>
        <?php echo !empty($Errors['contentLen']) ? '<div class="invalid-feedback">' . $Errors['contentLen'] . '</div>' : '' ?>
     </div>
   
    <div class="text-center">
        <button id="submit" type="submit" class="btn btn-primary submit">Valider</button>
    </div>
</form>

<?php
}

}
?>