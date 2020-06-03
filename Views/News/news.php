

<?php
if (!empty($News)) {

    foreach (array_chunk($News, 3, true) as $array) {

        echo '<div class="row">';

        foreach ($array as $new)
        {
        ?>

            <button class="content_single btn btn-secondary col-sm-4" cont="<?php echo WEBROOT . 'news/newsSingle/' . $new->getId() ?>">
                <h4 class="font-weight-bold mt-2 mb-2" aria-label="<?php echo $new->getNewsTitle() ?>" title="<?php echo $new->getNewsTitle() ?>"><?php echo $new->getNewsTitle() ?></h4>
                <small class=""><?php echo $new->getDatePosted() ?></small>
            </button>

        <?php
        }

        echo '</div>';

    }
}
else
{ ?>
    <div class="row">
        <div class="col-md text-center">
            <p>Pas de news à afficher.</p>        
        </div>
    </div>
<?php  
}
?>

