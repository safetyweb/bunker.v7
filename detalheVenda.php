<?php

//echo fnDebug('true');
$hashLocal = mt_rand();

@$cod_empresa = fnLimpacampo($_GET['cod_empresa']);
@$idVenda = fnLimpacampo($_GET['idVenda']);
@$opcao = fnLimpacampo($_GET['opcao']);

//verifica se vem da tela sem pop up
if (isset($_GET['pre'])) {
	$log_preconf = 'N';
} else {
	$log_preconf = 'S';
}

if (isset($_GET['pre']) && $log_preconf == 'S') {
	$cod_preconf = $_GET['pre'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		//fnEscreve($cod_empresa);

		if ($opcao != '') {
		}

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

//fnMostraForm();
//fnEscreve($qrBuscaNovo["COD_NOVO"]);
//fnEscreve($log_preconf);

?>

<?php if ($popUp != "true") { ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<div class="login-form">

						<div class="push10"></div>

						<table class="table" style="width: auto;">
							<tr>
								<th class="text-right"><small>Código</small></th>
								<th class="text-right"><small>Cód. Ext.</small></th>
								<th><small>Nome do Produto</small></th>
								<th class="text-right"><small>Qtd.</small></th>
								<th class="text-right"><small>Vl. Unitário</small></th>
								<th class="text-right"><small>Vl. Total</small></th>
							</tr>

							<?php
							$sql = "SELECT B.DES_PRODUTO,B.COD_EXTERNO, a.*   
														FROM itemvenda a
														LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
														WHERE a.COD_VENDA = '" . $idVenda . "'
												";

							//fnEscreve($sql);

							$totalDetalhe = 0;

							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

							while ($qrListaDetalheVenda = mysqli_fetch_assoc($arrayQuery)) {

								$totalDetalhe = $totalDetalhe + $qrListaDetalheVenda['VAL_TOTITEM'];

								if ($qrListaDetalheVenda['COD_EXCLUSA'] == 0) {
									$classeExc = "";
								} else {
									$classeExc = "text-danger";
								}

							?>
								<tr class="<?php echo $classeExc; ?>">
									<td class="text-right"><small><?php echo $qrListaDetalheVenda['COD_PRODUTO']; ?></small></td>
									<td class="text-right"><small><?php echo $qrListaDetalheVenda['COD_EXTERNO']; ?></small></td>
									<td><small><?php echo $qrListaDetalheVenda['DES_PRODUTO']; ?></small></td>
									<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['QTD_PRODUTO'], 2); ?></small></td>
									<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_UNITARIO'], 2); ?></small></td>
									<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_TOTITEM'], 2); ?></small></td>
								</tr>

							<?php
							}

							//fnEscreve($hojeSql);				  
							//fnEscreve($diasEstorno);				  
							//fnEscreve(var_dump($diasEstorno->diff($hojeSql)));				  
							?>
							<tr>
								<td><small><b>Total</b></small></td>
								<td class="text-right" colspan="5"><small><b><?php echo fnValor($totalDetalhe, 2); ?></b></small></td>
							</tr>

						</table>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>


	<script type="text/javascript">
		$(document).ready(function() {

			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',
				theme: 'bootstrap'
			});

			//icon picker
			$('.btnSearchIcon').iconpicker({
				cols: 8,
				iconset: 'fontawesome',
				rows: 6,
				searchText: 'Procurar  &iacute;cone'
			});

			$('.btnSearchIcon').on('change', function(e) {
				//console.log(e.icon);
				$("#DES_ICONE").val(e.icon);
			});

		});

		function retornaForm(index) {
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>