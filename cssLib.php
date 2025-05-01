	<?php
	if (isset($_SESSION["SYS_DES_CSSBASE"])) {
		$css_skin = $_SESSION["SYS_DES_CSSBASE"];
	} else {
		$css_skin = "bootstrap.flatly.min.css";
	}
	?>

	<link href="css/<?php echo $css_skin ?>" rel="stylesheet">

	<?php

	if (isset($_SESSION["SYS_DES_CSSAUX"])) {
		// fnEscreve($_SESSION["SYS_DES_CSSAUX"]);
	?>
		<link href="css/<?php echo $_SESSION['SYS_DES_CSSAUX'] ?>" rel="stylesheet">
	<?php
	}

	?>

	<script src="js/jquery.min.js"></script>

	<!-- JQUERY-CONFIRM -->
	<link href="css/jquery-confirm.min.css" rel="stylesheet" />

	<!-- extras -->
	<link href="css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="css/chosen-bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="css/fontawesome-pro-5.13.0-web/css/all.min.css" />
	<link href="css/bootstrap.vertical-tabs.css" rel="stylesheet" />

	<!-- mmenu -->
	<link href="js/plugins/mmenu/jquery.mmenu.css" type="text/css" rel="stylesheet" />
	<link href="js/plugins/tablesorter/css/theme.bootstrap_4.min.css" type="text/css" rel="stylesheet" />

	<!-- complement -->
	<link href="css/default.css?v=3" rel="stylesheet" />
	<link href="css/checkMaster.css" rel="stylesheet" />

	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="js/plugins/ie-emulation-modes-warning.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="images/favicon.ico" />

	<style>
		<?php if ($popUp != "true") { ?>body {
			background: #f2f3f4;
			/*background: #f2f3f4;*/
		}

		<?php } else { ?>body {
			background: #fff;
		}

		<?php } ?>
	</style>