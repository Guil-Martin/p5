<div class="container p-2 border">
    <h2>News</h2>

<?php 
if (!empty($News)) {
    foreach ($News as $new)
    {
    ?>
        <div class="row">

            <div class="col-sm-6 col-md-3  pt-2">
        
                <h4 class="font-weight-bold mb-2"><?php echo $new->getNewsTitle() ?></h4>

                <p class=""><?php echo $new->getDatePosted() ?></p>

                <button class="content_single btn btn-primary" cont="<?php echo WEBROOT . 'users/newsSingle/' . $new->getId() ?>">Button test</button>
    
            </div>

        </div>

    <?php
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

</div>