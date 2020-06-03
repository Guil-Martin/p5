<?php
if (!empty($Images)) {

    foreach (array_chunk($Images, 3, true) as $array) {

        echo '<div class="row">';

        foreach ($array as $image)
        {
            /*
            <button class="content_single btn btn-secondary col-sm-4" >
            </button>
            */
        ?>

            <div class="content_single card col-sm-4" cont="<?php echo WEBROOT . 'gallery/gallerySingle/' . $image->getId() ?>" style="width: 18rem;">
                <img class="card-img-top" src="<?php echo WEBROOT . 'images/gallery/' . $image->getImgThumbnail() ?>" alt="<?php $image->getImgTitle() ?>">
                <div class="card-text font-weight-bold mt-2 mb-2" aria-label="<?php echo $image->getImgTitle() ?>" title="<?php echo $image->getImgTitle() ?>"><?php echo $image->getImgTitle() ?></div>
                <small class=""><?php echo $image->getDatePosted() ?></small>
            </div>

        <?php
        }

        echo '</div>';

    }
}
else
{ ?>
    <div class="row">
        <div class="col-md text-center">
            <p>Pas d'images à afficher.</p>        
        </div>
    </div>
<?php  
}
?>