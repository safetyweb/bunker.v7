<?php
if ($cod_empresa != 311) {
	//inicialização
	$aba1049 = "";
	$aba1468 = "";
	$aba1022 = "";
	$aba1035 = "";
	$aba1050 = "";
	$aba1041 = "";
	$aba1039 = "";
	$aba1042 = "";
	$aba1056 = "";
	$aba1254 = "";
	$aba1182 = "";
	$aba1169 = "";
	$abaRegras = "";
	$abaComunica = "";
	$abaResultado = "";
	$abaCampanhaComp = "";

	switch ($abaCampanhas) {

			//Default
		case 1049: //personas
			$aba1049 = "active ";
			break;
		case 1468: //campanhas
			$aba1468 = "active ";
			break;
		case 1022: //vantagens
			$aba1022 = "active ";
			break;
		case 1050: //personas
			$aba1050 = "active ";
			break;
		case 1041: //modelo de negócio - set up
			$aba1041 = "active ";
			break;
		case 1042: //campanhas
			$aba1042 = "active ";
			break;
		case 1032: //campanhas
			$aba1032 = "active ";
			break;
		case 1039: //campanhas
			$aba1039 = "active ";
			break;
		case 1056: //ativacao
			$aba1056 = "active ";
			break;
		case 1169: //ativacao
			$aba1169 = "active ";
			break;
		case 1182: //relatórios
			$aba1182 = "active ";
			break;
		case 1254: //pesquisa
			$aba1254 = "active ";
			break;
			//default:
			//code to be executed if n is different from all labels;
	}

	if (isset($_GET['idc'])) {
		$cod_campanha = fnLimpaCampoZero(fnDecode(@$_GET['idc']));
		$sql = "SELECT TIP_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
		$qrCampanha = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
		$tip_campanha = $qrCampanha['TIP_CAMPANHA'];
	} else {
		$cod_campanha = 0;
		$tip_campanha = 0;
	}

	//echo "<pre>";	
	//print_r($modsAutorizados);	
	//echo "</pre>";

?>

	<ul class="breadcrumb">


		<?php if (fnControlaAcesso("1049", @$modsAutorizados) === true) { ?>
			<li class="<?php echo $aba1049;
						echo $abaPersonaComp; ?>"><a href="action.do?mod=<?php echo fnEncode(1049) . "&id=" . fnEncode($cod_empresa); ?>">Personas</a></li>
		<?php } else { ?>
			<li class="<?php echo $aba1049;
						echo $abaPersonaComp; ?> disabled"><a class="disabled">Personas</a></li>
		<?php } ?>

		<?php if (fnControlaAcesso("1468", @$modsAutorizados) === true) { ?>
			<li class="<?php echo $aba1468;
						echo $abaCampanhaComp; ?>"><a href="action.do?mod=<?php echo fnEncode(1468) . "&id=" . fnEncode($cod_empresa); ?>">Campanhas</a></li>
		<?php } else { ?>
			<li class="<?php echo $aba1468;
						echo $abaCampanhaComp; ?> disabled"><a class="disabled" disabled>Campanhas</a></li>
		<?php } ?>

		<?php
		if ($tip_campanha != 21) {
			if ($abaRegras == 'S') {
		?>
				<li class="<?php echo $aba1022;
							echo $abaRegrasComp; ?>"><a href="action.do?mod=<?php echo fnEncode(1022) . "&id=" . fnEncode($cod_empresa) . "&idc=" . @$_GET['idc']; ?>">Regras</a></li>
			<?php
			} else { ?>
				<li class="<?php echo $aba1022;
							echo $abaRegrasComp; ?> disabled"><a class="disabled">Regras</a></li>
		<?php
			}
		}
		?>

		<?php if ($abaComunica == 'S') { ?>
			<li class="<?php echo $aba1169;
						echo $abaComunicaComp; ?>"><a href="action.do?mod=<?php echo fnEncode(1169) . "&id=" . fnEncode($cod_empresa) . "&idc=" . @$_GET['idc']; ?>">Comunicação</a></li>
		<?php } else { ?>
			<li class="<?php echo $aba1169;
						echo $abaComunicaComp; ?> disabled"><a class="disabled">Comunicação</a></li>
		<?php } ?>



		<?php if ($abaResultado == 'S') { ?>
			<li class="<?php echo $aba1182;
						echo $abaResultadoComp; ?>"><a href="action.do?mod=<?php echo fnEncode(1182) . "&id=" . fnEncode($cod_empresa) . "&idc=" . @$_GET['idc']; ?>">Resultados</a></li>
		<?php } else { ?>

			<?php
			if (fnDecode($_GET['mod']) == 1495) {
			?>
				<li class="<?php echo $aba1182;
							echo $abaResultadoComp; ?> disabled"><a class="disabled">Resultados</a></li>
			<?php
			} else {
			?>
				<li class="<?php echo $aba1182;
							echo $abaResultadoComp; ?>"><a href="action.do?mod=<?php echo fnEncode(1182) . "&id=" . fnEncode($cod_empresa); ?>">Resultados</a></li>
			<?php
			}
			?>

		<?php } ?>

	</ul>

	<script type="text/javascript">
		$(document).ready(function() {

			$(".disabled").click(function(e) {
				e.preventDefault();
				return false;
			});

		});
	</script>

	<style>
		.breadcrumb {
			padding: 0px;
			background: #F3F5FA;
			list-style: none;
			overflow: hidden;
			margin-top: 0;
		}

		.breadcrumb>li+li:before {
			padding: 0;
		}

		.breadcrumb li {
			float: left;
			font-size: 16px;
		}

		.breadcrumb li.active a {
			background: gray;
			/* fallback color */
			background: #2c3e50;
			color: #fff;
		}

		.breadcrumb li.completed a {
			background: gray;
			/* fallback color */
			background: #82E0AA;
			color: #fff;
		}

		.breadcrumb li.active a:after {
			border-left: 30px solid #2c3e50;
		}

		.breadcrumb li.completed a:after {
			border-left: 30px solid #82E0AA;
		}

		.breadcrumb li a {
			color: #8093A7;
			text-decoration: none;
			padding: 25px 5px 25px 45px;
			position: relative;
			display: block;
			float: left;
		}

		.breadcrumb li a:after {
			content: " ";
			display: block;
			width: 0;
			height: 0;
			border-top: 50px solid transparent;
			/* Go big on the size, and let overflow hide */
			border-bottom: 50px solid transparent;
			border-left: 30px solid #F3F5FA;
			position: absolute;
			top: 50%;
			margin-top: -50px;
			left: 100%;
			z-index: 2;
		}

		.breadcrumb li a:before {
			content: " ";
			display: block;
			width: 0;
			height: 0;
			border-top: 50px solid transparent;
			/* Go big on the size, and let overflow hide */
			border-bottom: 50px solid transparent;
			border-left: 30px solid white;
			position: absolute;
			top: 50%;
			margin-top: -50px;
			margin-left: 5px;
			left: 100%;
			z-index: 1;
		}

		.breadcrumb li:first-child a {
			padding-left: 15px;
		}

		.breadcrumb li a:not(.disabled):hover {
			background: #ffc107;
			color: #fff;
		}

		.breadcrumb li a:not(.disabled):hover:after {
			border-left-color: #ffc107 !important;
		}

		.breadcrumb li a.disabled:hover {
			cursor: not-allowed;
		}
	</style>

<?php
	// FIM DO IF DA EMPRESA
}
?>