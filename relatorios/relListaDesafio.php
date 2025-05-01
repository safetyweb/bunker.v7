<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
		$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
		$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
		$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
		$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
		$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
	}
} else {
	$cod_empresa = 0;
	// $codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

}

//fnMostraForm();
//fnEscreve("QunXraEOVrg¢");

?>

<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div>

<!-- Portlet -->
<div class="portlet portlet-bordered">

	<div class="portlet-title">
		<div class="caption">
			<i class="far fa-terminal"></i>
			<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
		</div>

		<?php
		$formBack = "1048";
		include "atalhosPortlet.php"; ?>

	</div>

	<div class="push10"></div>

	<div class="portlet-body">

		<?php if ($msgRetorno <> '') { ?>
			<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php echo $msgRetorno; ?>
			</div>
		<?php } ?>

		<h3 style="margin: 0 0 30px 15px;">Crie ou edite <strong>Personas</strong> para seu <strong>Desafio</strong> </h3>

		<div id="div_refreshPersona"><!-- div controle ajax persona -->

			<div class="col-md-2">

				<div class="panelBox borda">
					<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Persona / <?php echo $nom_empresa; ?>">
						<i class="fas fa-plus fa-2x" aria-hidden="true" style="margin: 75px 0 75px 0;"></i>
					</div>
				</div>

			</div>

			<?php
			$sql = "select * from persona where cod_empresa = " . $cod_empresa . " and LOG_ATIVO = 'S' order by DES_PERSONA ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$count = 0;
			while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
				$count++;

				$sqlPersonas = "SELECT COUNT(B.COD_CLIENTE) as TOTAL_PERSONA FROM PERSONACLASSIFICA B WHERE B.COD_PERSONA = " . $qrListaPersonas['COD_PERSONA'] . " AND B.COD_EMPRESA = $cod_empresa ";
				//fnEscreve($sqlPersonas);
				$sqlPersonasquery = mysqli_query(connTemp($cod_empresa, ''), $sqlPersonas);
				$ListaPersonas = mysqli_fetch_assoc($sqlPersonasquery);

			?>

				<div class="col-md-2">

					<div class="panel">

						<div class="top primaryPanel" style="background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>">
							<a href="action.php?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>"><i class="fa <?php echo $qrListaPersonas['DES_ICONE'] ?> fa-3x iwhite" aria-hidden="true"></i></a>
							<a href="javascript:void(0);" class="btnEdit addBox" data-url="action.php?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
							<a href="action.php?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>">
								<h6 style="background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>"><?php echo $qrListaPersonas['DES_PERSONA'] ?></h6>
						</div>
						<div class="bottom">
							<h2><?php echo number_format($ListaPersonas['TOTAL_PERSONA'], 0, ",", "."); ?></h2>
							<h6>clientes participantes</h6>
							<!-- <?php echo $qrListaPersonas['COD_PERSONA']; ?> -->
						</div>
						</a>
					</div>

				</div>

			<?php
			}

			?>

			<div class="push"></div>

			<div class="panel-group" id="accordion">

				<div><!-- div controle do acordion -->

					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
								<button class="btn btn-sm btn-default"><i class="fas fa-archive"></i> Personas Arquivadas</button>
							</a>
						</h4>
					</div>
					<div id="collapse1" class="panel-collapse collapse">

						<div class="panel-body">

							<div class="row">

								<div class="push10"></div>

								<?php
								$sql = "select * from persona where cod_empresa = " . $cod_empresa . " and LOG_ATIVO <> 'S' order by DES_PERSONA ";
								//fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								$count = 0;
								while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
									$count++;
									$sqlPersonas = "CALL SP_BUSCA_PERSONA(" . $qrListaPersonas['COD_PERSONA'] . "," . $cod_empresa . ");";
									$sqlPersonasquery = mysqli_query(connTemp($cod_empresa, ''), $sqlPersonas);
									$ListaPersonas = mysqli_fetch_assoc($sqlPersonasquery);

								?>

									<div class="col-md-2">

										<div class="panel">
											<div class="top primaryPanel" style="background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>">
												<a href="action.php?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>"><i class="fa <?php echo $qrListaPersonas['DES_ICONE'] ?> fa-3x iwhite" aria-hidden="true"></i></a>
												<a href="javascript:void(0);" class="btnEdit addBox" data-url="action.php?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
												<a href="action.php?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>">
													<h6 style="background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>"><?php echo $qrListaPersonas['DES_PERSONA'] ?></h6>
											</div>
											<div class="bottom">
												<h2><?php echo number_format($ListaPersonas['TOTALCLI'], 0, ",", "."); ?></h2>
												<h6>clientes participantes </h6>
											</div>
											</a>
										</div>

									</div>

								<?php
								}


								?>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div><!-- fim div controle ajax persona -->

	</div>


	<div class="push30"></div>


	<div class="row">

		<h3 style="margin: 0 0 20px 15px;"><strong>Desafios</strong> que trazem <strong>Resultados</strong></h3>

		<div class="col-md-3">

			<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1375) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Desafio / <?php echo $nom_empresa; ?>"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Criar Novo Desafio</a>

		</div>

		<div class="push20"></div>

		<a name="campanha" />

		<div class="col-md-12">

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Nome do Desafio</th>
						<th class="text-center">Hits</th>
						<th class="text-center">Ativo</th>
						<th class="text-center">Data Início</th>
						<th class="text-center">Data Fim</th>
						<th class="text-center">Meta %</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody id="div_refreshDesafio">

					<?php
					$sql = "SELECT DESAFIO.*,
											(SELECT count(1) from DESAFIO_CONTROLE where DESAFIO_CONTROLE.COD_DESAFIO = DESAFIO.COD_DESAFIO) as hitsDesafio	
											FROM DESAFIO WHERE DESAFIO.COD_EMPRESA = $cod_empresa";
					//fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$count = 0;
					while ($qrListaDesafio = mysqli_fetch_assoc($arrayQuery)) {
						$count++;

						if ($qrListaDesafio['LOG_ATIVO'] == "S") {
							$desafioAtivo = "<i class='fas fa-check' aria-hidden='true' style='color: #18BC9C;'></i>";
						} else {
							$desafioAtivo = "<i class='fas fa-times' aria-hidden='true' style='color: #F00;'></i>";
						}

					?>

						<tr>
							<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaDesafio['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaDesafio['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaDesafio['NOM_DESAFIO']; ?></td>
							<td class='text-center'><?php echo fnValor($qrListaDesafio['hitsDesafio'], 0); ?></td>
							<td class='text-center'><?php echo $desafioAtivo; ?></td>
							<td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_INI']); ?></td>
							<td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_FIM']); ?></td>
							<td class="text-center"><small><?php echo fnValor($qrListaDesafio['VAL_METADES'], 2); ?></td>
							<td class='text-center'>
								<a class='btn btn-xs btn-info addBox' data-url="action.php?mod=<?php echo fnEncode(1375) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($qrListaDesafio['COD_DESAFIO']) ?>&pop=true" data-title="Desafio / <?php echo $qrListaDesafio['NOM_DESAFIO']; ?>"><i class='fas fa-pencil'></i> Editar </a>
							</td>
							<td class='text-center'>
								<a class='btn btn-xs btn-success' href="action.php?mod=<?php echo fnEncode(1376); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idD=<?php echo fnEncode($qrListaDesafio['COD_DESAFIO']); ?>"><i class='fas fa-external-link-square'></i> Acessar </a>
							</td>
						</tr>

					<?php
					}

					?>

				</tbody>
			</table>

		</div>

		<div class="push30"></div>

	</div>

	<div class="push10"></div>

</div>

</div><!-- fim Portlet body -->

</div><!-- fim Portlet  -->

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
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

<div class="push20"></div>

<form id="formModal">
	<input type="hidden" class="input-sm" name="REFRESH_DESAFIO" id="REFRESH_DESAFIO" value="N">
	<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N">
</form>

<script type="text/javascript">




</script>