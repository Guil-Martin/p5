<div class="container p-2 border border-secondary border-top-0">
    <h2>News</h2>

<?php 
if (!empty($News)) {
    foreach ($News as $new)
    {
    ?>
        <div class="row">

            <div class="col-sm-6 col-md-3  pt-2">
                <!-- Card -->
                <div class="card promoting-card">

                <!-- Card content -->
                <div class="card-body d-flex flex-row">

                    <!-- Content -->
                    <div>

                        <!-- Title -->
                        <h4 class="card-title font-weight-bold mb-2"><?php echo $new->getNewsTitle() ?></h4>
                        <!-- Subtitle -->
                        <p class="card-text"><?php echo $new->getDatePosted() ?></p>

                        </div>

                    </div>

                    <!-- Card image -->
                    <div class="view overlay">
                        
                        <button id="news_single" class="btn btn-primary" cont="<?php echo WEBROOT . 'users/newsSingle/' . $new->getId() ?>">Button test</button>
                    </div>

                    <!-- Card content -->
                    <div class="card-body">

                        <div class="collapse-content">

                        <!-- Text -->
                        <p class="card-text collapse" id="collapseContent">Recently, we added several exotic new dishes to our restaurant menu. They come from countries such as Mexico, Argentina, and Spain. Come to us, have some delicious wine and enjoy our juicy meals from around the world.</p>
                        <!-- Button -->
                        <a class="btn btn-flat red-text p-1 my-1 mr-0 mml-1 collapsed" data-toggle="collapse" href="#collapseContent" aria-expanded="false" aria-controls="collapseContent"></a>

                        </div>

                    </div>

                </div>
                <!-- Card -->
            </div>

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