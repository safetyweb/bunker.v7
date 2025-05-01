<style type="text/css">
	.blob {
		position: absolute;
		top: 8px;
		right: 1px;
		border-radius: 50%;
		box-shadow: 0 0 0 0 rgba(0, 0, 0, 1);
		margin: 10px;
		height: 11px;
		width: 11px;
		transform: scale(1);
		animation: pulse-black 2s infinite;
	}

	.blob.red {
		background: rgba(255, 82, 82, 1);
		box-shadow: 0 0 0 0 rgba(255, 82, 82, 1);
		animation: pulse-red 1.5s infinite;
	}

	@keyframes pulse-red {
		0% {
			transform: scale(0.95);
			box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.7);
		}

		70% {
			transform: scale(1);
			box-shadow: 0 0 0 10px rgba(255, 82, 82, 0);
		}

		100% {
			transform: scale(0.95);
			box-shadow: 0 0 0 0 rgba(255, 82, 82, 0);
		}
	}
</style>

<!-- top nav bar -->
<nav class="navbar navbar-default navbar-top menuCentral" style="border-radius: 0;">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<!-- <a class="navbar-brand" href="#" style="letter-spacing: -2px; font-size: 20px;">MARKA . ONE</a> -->
			<!--<a class="navbar-brand" href="#" style="margin-top: -5px;"><img src="media\clientes\marka_white_small.png"></a> -->
			<a class="navbar-brand navbar-brand-menu btnMenu" href="#menu">
				<i class="fa fa-bars" aria-hidden="true"></i>
				<div class="menuLateralResponsivo">Menu</div>
			</a>
			<?php

			// echo $_SESSION['SYS_COD_SISTEMA'];

			$cod_empresa = fnLimpaCampoZero(fnDecode(@$_GET['id']));

			$sqlLogo2 = "SELECT DES_LOGOMAIN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
			// fnEscreve($sqlLogo2);
			$qrLogo2 = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlLogo2));
			$des_logo = @$qrLogo2['DES_LOGOMAIN'];
			$logoEmp = "media/clientes/$cod_empresa/logotipo/$des_logo";
			$larguraSistema = "150px";
			$larguraEmpresa = "200px";

			if ($_SESSION['SYS_COD_SISTEMA'] == 18) {

				$sqlTipo = "SELECT TIP_LOGO FROM EMPRESAS WHERE COD_EMPRESA = $_SESSION[SYS_COD_EMPRESA]";
				$qrTipo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlTipo));

				$sqlLogo = "SELECT DES_LOGO_LGT, DES_LOGO_DRK FROM SISTEMAS WHERE COD_SISTEMA = $_SESSION[SYS_COD_SISTEMA]";
				$qrLogo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlLogo));



				$tip_logo = $qrTipo['TIP_LOGO'];
				$des_logo_lgt = $qrLogo['DES_LOGO_LGT'];
				$des_logo_drk = $qrLogo['DES_LOGO_DRK'];

				if ($tip_logo == 0) {
					$logoHeader = "media/clientes/0/logoSistema/$des_logo_lgt";
				} else {
					$logoHeader = "media/clientes/0/logoSistema/$des_logo_drk";
				}
			} else {
				$logoHeader = "images\logo_bunker_sm.png";
				$larguraSistema = "120px";
			}

			if ($logoHeader != "") {
			?>

				<style>
					.logoHeader {
						width: <?= $larguraSistema ?>;
					}

					.logoEmp {
						width: <?= $larguraEmpresa ?>;
					}

					.imgPipe {
						height: 35px;
						width: 2px;
					}

					@media screen and (max-width: 1024px) {
						.logoHeader {
							width: 75px;
						}

						.logoEmp {
							width: 130px;
						}

						.imgPipe {
							height: 25px;
							width: 1.5px;
						}

						.navbar-brand-menu {
							width: unset;
						}

						.caseLogo {
							padding-left: 0;
							padding-right: 0;
						}
					}
				</style>

				<a class="navbar-brand caseLogo" href="#" style="margin-top: -5px;">
					<div class="col-xs-5 text-right" style="padding: 0;">
						<img class="logoHeader" src="<?= $logoHeader ?>">
					</div>
					<?php if ($des_logo != "" && $cod_empresa != 77 && $cod_empresa != 85) { ?>
						<div class="col-xs-1 text-center" style="padding: 0;">
							<img class="imgPipe" src="media/pipeline.png" width="2px">
						</div>
						<div class="col-xs-6" style="padding: 0;">
							<img class="logoEmp" src="<?= $logoEmp ?>">
						</div>
					<?php } ?>
				</a>
			<?php
			}
			?>

			<!-- <a class="navbar-brand" href="#" style="margin-top: -5px;"></a> -->
		</div>

		<div id="navbar" class="navbar-collapse collapse">

			<ul class="nav navbar-nav navbar-right">

				<li>
					<?php

					$sqlNotifica = "SELECT COD_NOTIFICACAO FROM NOTIFICACOES WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO] AND DAT_LEITURA IS NULL";
					// fnEscreve($sqlNotifica);
					$arrayNotifica = mysqli_query($connAdm->connAdm(), $sqlNotifica);

					?>
					<a href="javascript:void(0)" id="notificacaoSino" data-url="action.do?mod=<?php echo fnEncode(1585) ?>&pop=true" style="margin-top: 3px;" data-title="Central de notificações">
						<?php
						if (mysqli_num_rows($arrayNotifica) > 0) {
						?>
							<div class="blob red"></div>
						<?php
						}
						?>
						<span class="f18 fal fa-bell"></span>
					</a>
				</li>
				<li></li>

				<?php
				//não monta help para sistema que não são da marka
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 3: //adm marka
					case 4: //fidelidade
					case 13: //sh manager
					case 14: //rede duque
					case 18: //mais cash
				?>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fal fa-headset"></span> &nbsp;Suporte <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?php
								switch ($_SESSION["SYS_COD_SISTEMA"]) {
									case 3: //adm marka
								?>
										<li><a href="action.php?mod=<?php echo fnEncode(1434); ?>" role="button">Criar chamado </a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1433); ?>" role="button">Lista de chamados </a></li>
									<?php
										break;

									default: //outras
									?>
										<li><a href="action.php?mod=<?php echo fnEncode(1278); ?>&id=<?php echo fnEncode($_SESSION["SYS_COD_EMPRESA"]); ?>" role="button">Criar chamado </a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1280); ?>&id=<?php echo fnEncode($_SESSION["SYS_COD_EMPRESA"]); ?>" role="button">Lista de chamados </a></li>
									<?php
										break;
								}
								//menu acesso help desk master	
								//acesso Katia
								if (($_SESSION["SYS_COD_EMPRESA"] == 2) or ($_SESSION["SYS_COD_USUARIO"] == 22529)) {
									//if ($_SESSION["SYS_COD_EMPRESA"] == 2) {
									?>
									<li><a href="action.php?mod=<?php echo fnEncode(1282); ?>" role="button">Lista de chamados (ADM) </a></li>
								<?php
								}
								?>
							</ul>
						</li>

				<?php
						//fim se sistemas não são Marka	
						break;
				}
				?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fal fa-chart-network"></span> &nbsp;Sistema <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php
						$sql = "";
						$sql = "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN(" . $_SESSION["SYS_COD_SISTEMAS"] . ") ";
						$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
						//fnTestesql($connAdm->connAdm(),$sql);

						while ($qrSistemasUsuario = mysqli_fetch_assoc($arrayQuery)) {
							if ((int)$_SESSION["SYS_COD_SISTEMA"] == (int)$qrSistemasUsuario['COD_SISTEMA']) {
								$nomeSistema = '<b>' . $qrSistemasUsuario['DES_SISTEMA'] . '</b>';
							} else {
								$nomeSistema = $qrSistemasUsuario['DES_SISTEMA'];
							}
							echo "
						  <li><a href='action.php?sys=" . fnEncode($qrSistemasUsuario['COD_SISTEMA']) . "'>" . $nomeSistema . "</a></li> 
						";
						}
						?>
					</ul>
				</li>
				<?php
				if ($desApelidoUsuarioLogado == '0') {
					$nomUsuarioSistemaLogado = $_SESSION["SYS_NOM_USUARIO"];
				} else {
					$nomUsuarioSistemaLogado = $desApelidoUsuarioLogado;
				}

				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fal fa-user-cog"></span> &nbsp;<?php echo $nomUsuarioSistemaLogado; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="action.do?mod=<?php echo fnEncode(1124); ?>"><span class="glyphicon glyphicon-cog"></span>&nbsp; Meus Dados</a></li>
						<li><a href="action.do?mod=<?php echo fnEncode(1125); ?>"><span class="fa fa-unlock-alt"></span>&nbsp; Troca de Senha</a></li>
						<!--
				<li><a href="#"><span class="glyphicon glyphicon-star"></span>&nbsp; Favoritos</a></li>
				<li><a href="#"><span class="glyphicon glyphicon-bell"></span>&nbsp; Notificações</a></li>
				-->
						<li role="separator" class="divider"></li>
						<li><a href="<?php fnurl(); ?>_system/seguranca.do?logoff=1"><span class="fal fa-power-off"></span>&nbsp; Log Off</a></li>
					</ul>
				</li>
				<li><a href="<?php fnurl(); ?>_system/seguranca.do?logoff=1" style="margin-top: 3px;"><span class="fal fa-power-off"></span></a></li>

				</li>

			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
<!-- end top nav bar -->
<script type="text/javascript">
	$(function() {
		$("#notificacaoSino").click(function() {
			var popLink = $(this).attr("data-url");
			var popTitle = $(this).attr("data-title");
			$("#popModalNotifica iframe").attr({
				'src': popLink
			});
			if (popTitle) {
				$("#popModalNotifica .modal-title").text(popTitle);
			} else {
				$("#popModalNotifica .modal-title").text("");
			}
			$('#popModalNotifica').appendTo("body").modal('show');
		});
	});
</script>
<!-- modal -->
<div class="modal fade" id="popModalNotifica" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->