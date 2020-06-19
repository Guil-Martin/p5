
<?php
if (!empty($User)) {
?>

<?php // echo '<p class="mt-2">' . $User->getUserName() . '</p>' ?>

<?php
}
?>

<div class="row">
<div class="col">

<form method='post' action="" id="filters" cont="<?php echo WEBROOT . 'home/posts/1'?>">

<div class="row">
    <div class="col-sm">
        <div class="row">

            <div class="form-group col">
                <label for="period">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="gallery">Gallerie</option>
                    <option value="news">Nouvelles</option>        
                </select>
            </div>
            <div class="form-group col">
                <label for="period">Période</label>
                <select class="form-control" id="period" name="period">
                    <option>--</option>
                    <option value="24">24h</option>
                    <option value="136">7 jours</option>
                    <option value="5040">1 mois</option>
                    <option value="60480">1 année</option>
                </select>
            </div>

        </div>
    </div>

    <div class="col-sm">
        <div class="row">

            <div class="form-group col">
                <label for="sel1">Classé par</label>
                <select class="form-control" id="number" name="number">
                    <option>--</option>
                    <option value="views">Vues</option>
                    <option value="likes">J'aime</option>
                    <option value="comments">Commentaires</option>
                </select>
            </div>
            <div class="form-group col">
                <label for="sel1">Ordre</label>
                <select class="form-control" id="order" name="order">
                    <option value="recent">Plus récent</option>
                    <option value="older">Plus vieux</option>
                </select>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="text-center">
            <button id="submit" type="submit" class="btn btn-primary pl-4 pr-4 mb-2">Filtrer</button>
        </div>
    </div>
</div>

</form>

</div>
</div>

<div class="row">
<div class="homePageContent col-sm">

</div>
</div>