<?php 
if (!empty($User)) {
?>

<div class="row mb-3">
    <div class="col">
        <h2 class="text-center">A propos</h2>
        <div class="p-2">
            <?php echo $User->getBio(); ?>
        </div>
    </div>
</div>

<div class="row pb-2">
    <div class="col">
        <h2 class="text-center">Statistiques</h2>       
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3 p-0 d-flex statBox">
                <div class="m-2 p-1 bg-dark d-flex align-items-center justify-content-center text-center">
                    <div>
                        <span class="pl-1">Vues des images</span>
                        <div class="statNum">
                            <strong><?php echo $NumViewsImg ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 p-0 d-flex statBox"">
                <div class="m-2 p-1 bg-dark d-flex align-items-center justify-content-center text-center">
                    <div>
                        <span class="pl-1">Vues des nouvelles</span>
                        <div class="statNum">
                            <strong><?php echo $NumViewsNews ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 p-0 d-flex statBox">
                <div class="m-2 p-1 bg-dark d-flex align-items-center justify-content-center text-center">
                    <div>
                        <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                        </svg>
                        <span class="pl-1">des images</span>
                        <div class="statNum">
                            <strong><?php echo $NumLikesImg ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 p-0 d-flex statBox">
            <div class="m-2 p-1 bg-dark d-flex align-items-center justify-content-center text-center">
                    <div>
                        <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                        </svg>
                        <span class="pl-1">des nouvelles</span>
                        <div class="statNum">
                            <strong><?php echo $NumLikesNews ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
}
?>