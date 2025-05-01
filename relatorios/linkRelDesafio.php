<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_servidor = "";
$des_servidor = "";
$des_abrevia = "";
$des_geral = "";
$cod_operacional = "";
$des_observa = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_segmentEmp = "";
$codEmpresa = "";
$qrListaDesafio = "";
$desafioAtivo = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_servidor = fnLimpaCampoZero(@$_REQUEST['COD_SERVIDOR']);
		$des_servidor = fnLimpaCampo(@$_POST['DES_SERVIDOR']);
		$des_abrevia = fnLimpaCampo(@$_POST['DES_ABREVIA']);
		$des_geral = fnLimpaCampo(@$_POST['DES_GERAL']);
		$cod_operacional = fnLimpaCampoZero(@$_POST['COD_OPERACIONAL']);
		$des_observa = fnLimpaCampo(@$_POST['DES_OBSERVA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
//fnEscreve($DestinoPg);

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
		include "atalhosPortlet.php"; ?>

	</div>

	<div class="push10"></div>

	<div class="row">

		<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">

			<div class="col-md-12">

				<table class="table table-bordered table-striped table-hover">

					<thead>
						<tr>
							<th></th>
							<th>Nome do Desafio</th>
							<th class="text-center">Hits</th>
							<th class="text-center">Ativo</th>
							<th class="text-center">Data Início</th>
							<th class="text-center">Data Fim</th>
							<th class="text-center">Meta %</th>
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
								<td class='text-center'><input type="radio" name="radio1" onclick="retornaForm('<?php echo $count; ?>')"></th>
								<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaDesafio['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaDesafio['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaDesafio['NOM_DESAFIO']; ?></td>
								<td class='text-center'><?php echo fnValor($qrListaDesafio['hitsDesafio'], 0); ?></td>
								<td class='text-center'><?php echo $desafioAtivo; ?></td>
								<td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_INI']); ?></td>
								<td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_FIM']); ?></td>
								<td class="text-center"><small><?php echo fnValor($qrListaDesafio['VAL_METADES'], 2); ?></td>
							</tr>

							<input type="hidden" id="ret_ID_<?php echo $count; ?>" value="<?php echo fnEncode($qrListaDesafio['COD_EMPRESA']); ?>">
							<input type="hidden" id="ret_IDD_<?php echo $count; ?>" value="<?php echo fnEncode($qrListaDesafio['COD_DESAFIO']); ?>">

						<?php
						}

						?>

					</tbody>

				</table>

			</div>

		</form>

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

<?php
if (!is_null($RedirectPg)) {
	$DestinoPg = fnEncode($RedirectPg);
} else {
	$DestinoPg = "";
}
?>

<script type="text/javascript">
	function retornaForm(index) {

		$("#codBusca").val($("#ret_ID_" + index).val());
		$("#codBusca").val($("#ret_IDP_" + index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_ID_" + index).val() + '&idD=' + $("#ret_IDD_" + index).val());
		$('#formLista').submit();
	}
</script>