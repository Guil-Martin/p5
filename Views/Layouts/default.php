<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="fr"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Site gallerie</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Site gallerie" />
	<meta name="keywords" content="bootstrap, html5, css3, responsive" />
	<meta name="author" content="Guillaume Martin" />

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<link rel="shortcut icon" href="<?php echo WEBROOT ?>assets/favicon/favicon.png">

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?php echo WEBROOT ?>assets/css/bootstrap.css">
	
	<link rel="stylesheet" href="<?php echo WEBROOT ?>assets/css/style.css">

	</head>
	<body>

	<div id="page">

	<header>
	
		<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
			<a class="navbar-brand" href=<?php echo WEBROOT ?>>
				<svg class="bi bi-house-door-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M6.5 10.995V14.5a.5.5 0 01-.5.5H2a.5.5 0 01-.5-.5v-7a.5.5 0 01.146-.354l6-6a.5.5 0 01.708 0l6 6a.5.5 0 01.146.354v7a.5.5 0 01-.5.5h-4a.5.5 0 01-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z"/>
					<path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/>
				</svg>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<?php if (CONNECTED)					
					/*
					<img src="<?php echo WEBROOT . 'images/users/' . $User->getAvatarThumb() ?>" alt="<?php $User->getUserName() ?>" class="img-fluid">
					*/
					{ ?>
					<li class="nav-item active">
						<a class="nav-link" href="<?php echo WEBROOT . 'users/page/' . $_SESSION["userContentId"] ?>" class="href">
						<?php echo $_SESSION["userName"] ?>
						</a>
					</li>
					<?php }
					else
					{ ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo WEBROOT . 'users/register' ?>">
						<svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
						</svg>
						S'enregistrer
						</a>
                    </li>
					<li class="nav-item active">
						<a class="nav-link" href="<?php echo WEBROOT . 'users/login' ?>">
						<svg class="bi bi-person-lines-fill" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7 1.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zm-2-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm0-3a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5zm2 9a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
						</svg>
						Connexion
						</a>
					</li>
					<?php }
					?>
					
				</ul>
			</div>
		</nav>

	</header>

	<div class="container">

		<?php echo $content_for_layout; ?>

		<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-xl" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h4 class="modal-title w-100" id="myModalLabel"></h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body p-0">

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
					</div>

				</div>
			</div>
		</div>

	</div>

	<footer class="pt-5 text-center">
		<p><sm>&copy; 2020 - GM - OPC <br><a href="https://github.com/GuillaumeM-OPC/p5" target="_blank">GitHub</a></p>
	</footer>

	</div>
	
	<!-- jQuery -->
	<script src="<?php echo WEBROOT ?>assets/js/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="<?php echo WEBROOT ?>assets/js/bootstrap.min.js"></script>
	<!-- Main JS -->
	<script src="<?php echo WEBROOT ?>assets/js/main.js"></script>
	<!-- Tinymce -->
	<script src="https://cdn.tiny.cloud/1/174jf85zpov9rbn2319xj4d1df7zegfj9wfg3g1ecfmdkq1h/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

	</body>
</html>