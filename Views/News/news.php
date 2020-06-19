<?php

function shortNumber($num)
{
    $units = ['', 'K', 'M', 'B', 'T'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1) . $units[$i];
}

if (!empty($News)) {
?>

<div class="row">
    <div class="col-sm-12 d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item"><button class="page-link <?php echo ($CurrentPage - 1) < 1 ? 'd-none' : '' ?>" cont="<?php echo WEBROOT . 'news/news/' . $User->getId() . '/' . ($CurrentPage - 1) ?>">❮</button></li>
            <?php 
            $startPoint = $CurrentPage - 3;
            $endPoint = $CurrentPage + 3;
            if (($CurrentPage - 3) < 1) { $startPoint = 0; }
            if (($CurrentPage + 3) > $NumPages) { $endPoint = $NumPages; }            
            while ($startPoint < $endPoint) { $startPoint++; ?>
                <li class="page-item <?php echo $startPoint == $CurrentPage ? 'active' : '' ?> d-none d-sm-block">
                <button class="page-link" cont="<?php echo WEBROOT . 'news/news/' . $User->getId() . '/' . $startPoint ?>"><?php echo $startPoint ?></button></li>
            <?php } ?>
            <li class="page-item"><button class="page-link <?php echo ($CurrentPage + 1) > $NumPages ? 'd-none' : '' ?>" cont="<?php echo WEBROOT . 'news/news/' . $User->getId() . '/' . ($CurrentPage + 1) ?>">❯</button></li>
            <li class="page-item"><div class="page-link"><?php echo 'Page ' . $CurrentPage . '/' . $NumPages ?></div></li>
            <li class="page-item">
                <form action="" id="pageSelect" class="form-inline" cont="<?php echo WEBROOT . 'news/news/' . $User->getId() ?>">
                    <div class="form-group">
                        <select class="form-control" id="pageSelector" name="pageSelector">
                            <?php for ($i=0; $i < $NumPages; $i++) { ?>
                                <option value="<?php echo ($i+1) ?>" <?php echo (($i+1) == $CurrentPage) ? "selected" : "" ?>><?php echo ($i+1) ?></option>                       
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button id="submit" type="submit" class="btn btn-secondary submit">OK</button>
                    </div>
                </form>
            </li>        
        </ul>
    </div>
</div>

<?php
foreach (array_chunk($News, 3, true) as $array) {

    echo '<div class="row">';

    foreach ($array as $new)
    {
    ?>

    <div class="content_single col-sm-4 p-1" style="height: 200px;" cont="<?php echo WEBROOT . "news/newsSingle/" . $new->getId() ?>">

        <div class="postContainer bg-dark" style="height: 100%;">

            <div class="date p-1">
                <small class="">Posté le <?php echo date("d/m/Y à H:i", strtotime($new->getDatePosted())) ?></small>
            </div>

            <div class="title text-center mt-5">
                <h5 class="font-weight-bold" aria-label="<?php echo $new->getNewsTitle() ?>" title="<?php echo $new->getNewsTitle() ?>"><?php echo $new->getNewsTitle() ?></h5>
            </div>

            <div class="stats text-center">

                <div class="row">

                    <div class="views col">
                        <svg class="bi bi-eye" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                            <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        <small class="pl-1"><?php echo shortNumber($new->getViews()) ?></small>
                    </div>

                    <div class="likes col">
                        <svg class="bi bi-hand-thumbs-up" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                        </svg>
                        <small class="pl-1"><?php echo shortNumber($new->getLikes()) ?></small>
                    </div>
                    
                    <div class="coms col">
                        <svg class="bi bi-chat-square-dots" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h2.5a2 2 0 0 1 1.6.8L8 14.333 9.9 11.8a2 2 0 0 1 1.6-.8H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        <small class="pl-1"><?php echo shortNumber($new->getComments()) ?></small>
                    </div>

                </div>

            </div>

            <?php if (!empty($User) && !empty($Owner)) 
            { ?>
            
            <div class="buttons">
                <button class="btn btn-light" aria-label="Editer" title="Editer" cont="<?php echo WEBROOT . "news/newsEdit/" . $User->getId() . "/" . $new->getId() ?>">
                    <svg class="bi bi-screwdriver" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M0 1l1-1 3.081 2.2a1 1 0 0 1 .419.815v.07a1 1 0 0 0 .293.708L10.5 9.5l.914-.305a1 1 0 0 1 1.023.242l3.356 3.356a1 1 0 0 1 0 1.414l-1.586 1.586a1 1 0 0 1-1.414 0l-3.356-3.356a1 1 0 0 1-.242-1.023L9.5 10.5 3.793 4.793a1 1 0 0 0-.707-.293h-.071a1 1 0 0 1-.814-.419L0 1zm11.354 9.646a.5.5 0 0 0-.708.708l3 3a.5.5 0 0 0 .708-.708l-3-3z"/>
                    </svg>
                </button>                
                <button class="del btn btn-danger" aria-label="Supprimer" title="Supprimer" cont="<?php echo WEBROOT . "news/delPost/" . $new->getId() . "/" . $User->getId() ?>">
                    <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </button>
            </div>
            
            <?php } ?>

        </div>

    </div>


    <?php }

    echo '</div>';

}

}
else
{ ?>
    <div class="row no gutters">
        <div class="col-md text-center">
            <p>Pas de news à afficher.</p>        
        </div>
    </div>
<?php  
}
?>