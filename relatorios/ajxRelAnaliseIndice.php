<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$meses = '';


$opcao = fnLimpaCampo(@$_GET['opcao']);
$acao = fnLimpaCampo(@$_GET['acao']);
$cod_empresa = fnDecode(@$_GET['id']);
$lojasSelecionadas = @$_REQUEST['LOJAS'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

switch ($opcao) {
	case 'quantidadeVendas':
		$nom_col = "Qtd. Vendas";
		$casasDecVl = 0;
		break;
	case 'clientesCompras':
		$nom_col = "Qtd. Clientes";
		$casasDecVl = 0;
		break;
	case 'totalVendas':
		$nom_col = "Vl. Vendas";
		$casasDecVl = 2;
		break;
	case 'totalCreditosExpirados':
		$nom_col = "Vl. Créditos Exp.";
		$casasDecVl = 2;
		break;
	case 'saldoCreditos':
		$nom_col = "Vl. Créditos";
		$casasDecVl = 2;
		break;
	case 'qtdItensAtendimento':
		$nom_col = "Qtd. Itens";
		$casasDecVl = 2;
		$OUTROS = 1;
		break;
	case 'totalCreditosResgatados':
		$nom_col = "Vl. Resgatado";
		$casasDecVl = 2;
		break;
	case 'clientesCadastrados':
		$nom_col = "Qtd. Clientes";
		$casasDecVl = 0;
		break;
	case 'clientesPrimeiraCompra':
		$nom_col = " Qtd. Clientes";
		$casasDecVl = 0;
		break;
	case 'clientesUltimaCompra':
		$nom_col = "Qtd. Clientes";
		$casasDecVl = 0;
		break;
	case 'quantidadeResgates':
		$nom_col = "Qtd. Resgates";
		$casasDecVl = 0;
		break;
	case 'clientesResgates':
		$nom_col = "Qtd. Clientes";
		$casasDecVl = 0;
		break;
	default:
		$nom_col = "Valor TM";
		$casasDecVl = 2;
		break;
}

switch ($acao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		//============================

		$sql = "CALL SP_RELAT_ANALISE_INDICE ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas' , $cod_empresa, '$opcao' )";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$row['VALOR'] = fnValor($row['VALOR'], 2);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		break;

	default:

?>

		<table class="table table-bordered table-striped table-hover tablesorter">
			<thead>
				<tr>
					<th><small>Loja</small></th>
					<th class="text-center"><small><?= $nom_col ?></small></th>
					<?php

					for ($i = 0; $i < $meses; $i++) {
					?>
						<th class="text-center"><small><?= date('m/Y', strtotime($dat_ini . "+ " . $i . "months")) ?></small></th>
					<?php
					}

					?>
				</tr>
			</thead>
			<tbody>

				<?php

				// Filtro por Grupo de Lojas
				include "../filtroGrupoLojas.php";

				$sql = "CALL SP_RELAT_ANALISE_INDICE ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas' , $cod_empresa, '$opcao' )";
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$count = 0;
				$countMeses = 0;
				$loja = "";

				switch ($opcao) {

					case "ticketMedio":
					case "totalVendas":
					case "totalCreditosExpirados":
					case "saldoCreditos":
					case "totalCreditosResgatados":
						$casasDec = 2;
						$sifrao = "R$ ";
						$alinha = "text-right";
						break;
					case 'qtdItensAtendimento':
						$casasDec = 2;
						$sifrao = "";
						$alinha = "text-right";
						break;
					default:
						$casasDec = 0;
						$sifrao = "";
						$alinha = "text-center";
						break;
				}

				while ($qrAnalise = mysqli_fetch_assoc($arrayQuery)) {
					$count++;
					$countMeses++;


					if ($loja != $qrAnalise['LOJA']) {

						echo "
								<tr>
								  <td><small>" . $qrAnalise['LOJA'] . "</small></td>
								  <td class='" . $alinha . "'><small>" . $sifrao . fnValor($qrAnalise['VALOR'], $casasDec) . "</small></td>
								";

						$loja = $qrAnalise['LOJA'];
					} else {

						echo "<td class='" . $alinha . "'><small>" . $sifrao . fnValor($qrAnalise['VALOR'], $casasDec) . "</small></td>";
					}

					@$totValor += @$qrAnalise['VALOR'];

					if ($countMeses == $meses) {
						echo "</tr>";
						$countMeses = 0;
					}
				}

				?>


			</tbody>

			<tfoot>
				<tr>
					<th></th>
					<th class='<?= $alinha ?>'><b><?= $sifrao ?> <?= fnValor($totValor, $casasDec) ?></b></th>
				</tr>
				<tr>
					<th colspan="100">
						<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
					</th>
				</tr>
				<script type="text/javascript">
					$(function() {
						$(".exportarCSV").click(function() {
							$.confirm({
								title: 'Exportação',
								content: '' +
									'<form action="" class="formName">' +
									'<div class="form-group">' +
									'<label>Insira o nome do arquivo:</label>' +
									'<input type="text" placeholder="Nome" class="nome form-control" required />' +
									'</div>' +
									'</form>',
								buttons: {
									formSubmit: {
										text: 'Gerar',
										btnClass: 'btn-blue',
										action: function() {
											var nome = this.$content.find('.nome').val();
											if (!nome) {
												$.alert('Por favor, insira um nome');
												return false;
											}

											$.confirm({
												title: 'Mensagem',
												type: 'green',
												icon: 'fa fa-check-square-o',
												content: function() {
													var self = this;
													return $.ajax({
														url: "relatorios/ajxRelAnaliseIndice.do?acao=exportar&opcao=<?= $opcao ?>&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
														data: $('#formulario').serialize(),
														method: 'POST'
													}).done(function(response) {
														self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
														var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
														SaveToDisk('media/excel/' + fileName, fileName);
														console.log(response);
													}).fail(function() {
														self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
													});
												},
												buttons: {
													fechar: function() {
														//close
													}
												}
											});
										}
									},
									cancelar: function() {
										//close
									},
								}
							});
						});
					});
				</script>
			</tfoot>

		</table>

<?php

		break;
}

?>