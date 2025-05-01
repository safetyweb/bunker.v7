<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

///echo fnDebug('true');
////fnEscreve('Entra no ajax');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$cod_empresa = fnLimpaCampoZero($_GET['id']);
$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
if (isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

if (isset($_GET['itens'])) {
	$itens = intval($_GET['itens']);

	$limit = "LIMIT $itens, 20";
}

////fnEscreve($cod_empresa);

switch ($acao) {

	case "gravar": //Rotina de gravação da planilha na tabela 'temporária'

		$sql = "DELETE FROM IMPORT_PRODEXTRA WHERE COD_EMPRESA = $cod_empresa";
		mysqli_query(connTemp($cod_empresa, ""), trim($sql));


		if (isset($_FILES['arquivo'])) {
			$errors = "";
			$file_name = $_FILES['arquivo']['name'];
			$file_size = $_FILES['arquivo']['size'];
			$file_tmp = $_FILES['arquivo']['tmp_name'];
			$file_type = $_FILES['arquivo']['type'];

			$arquivo = array(
				'CAMINHO_TMP' => $file_tmp,
				'CONADM' => $connAdm->connAdm()
			);

			$retorno = fnScan($arquivo);

			if ($retorno['RESULTADO'] == 0) {

				$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
				//$reader->setShouldFormatDates(true);

				$reader->open($file_tmp);

				$duplicado = 0;
				$ultimo_cod = 0;
				$insert = "";

				/*
				Glossário do array da planilha:

				$row[0] =  COD_EXTERNO
				$row[1] =  QTD_EXTRA
				$row[2] =  GANHA
				$row[3] =  LIMITE_DE_USO
				$row[3] =  LIMITE_QTD_PRODUTO
				*/

				$produtosCad = array();

				foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					$teste = 0;

					foreach ($sheet->getRowIterator() as $row) {
						if ($contador == 0) {
							$colunas = array_filter($row, create_function('$a', 'return preg_match("#\S#", $a);'));
							////fnEscreve(count($colunas));
						} else if ($contador != 0 && count($colunas) != 0) {

							//Adicionado por Lucas Ref chamado #6518 adicionado logica para verificar produtos repetidos no mesmo import
							$cod_externo1 = fnLimpaCampo(trim($row[0]));

							if (in_array($cod_externo1, $produtosCad)) {

								$duplicado++;
							} else {

								//buscando string sql pelo código externo do produto
								if (strpos(@$sql1, fnLimpaCampo(trim($row[0])))) {
									//incrementando o contador caso o cod externo seja duplicado (para informar o nro de registros duplicados)
									$duplicado++;
								} else {


									if (fnLimpaCampo(trim($row[0])) != $ultimo_cod && fnLimpaCampo(trim($row[0])) != "") {

										$ultimo_cod = fnLimpaCampo(trim($row[0]));
										$produtosCad[] = $ultimo_cod;

										if ($row[5] != "") {
											$qtd_limitProd = fnLimpaCampoZero(trim($row[5]));
										} else {
											$qtd_limitProd = 0;
										}

										$insert .= "(
										'$cod_empresa',
										'$cod_campanha',
										'" . fnLimpaCampo(trim($row[0])) . "',
										'" . fnValorSql(trim($row[1])) . "',
										'" . fnLimpaCampo(trim($row[2])) . "',
										'" . fnLimpaCampoZero(trim($row[3])) . "',
										'" . fnLimpaCampoZero(trim($row[4])) . "',
										'" . $qtd_limitProd . "',
										'" . fnLimpaCampoZero($cod_usucada) . "'
									),";
									} else {
										//incrementando o contador caso o cod externo seja duplicado (para informar o nro de registros duplicados)
										if ($row[0]) {
											$ultimo_cod = fnLimpaCampo(trim($row[0]));
											$duplicado++;
										}
									}
								}
							}
						} else {
							echo 'A planilha deve conter exatamente 5 colunas: "COD_EXTERNO", "QTD_EXTRA", "GANHA", "LIMITE_DE_USO", "LIMIT_QTD_PRODUTO". Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
				}
				//fnEscreve($sql1);
				if ($insert != "") {

					$insert = rtrim($insert, ',');

					$sql1 = "INSERT INTO IMPORT_PRODEXTRA(
					COD_EMPRESA,
					COD_CAMPANHA,
					COD_EXTERNO,
					QTD_FAIXEXT,
					TIP_FAIXEXT,
					TIP_CALCULO,
					QTD_FAIXLIM,
					QTD_LIMITPRODU,
					COD_USUCADA
				) VALUES $insert";

					// fnEscreve($sql1);
					// exit();

					mysqli_query(connTemp($cod_empresa, ""), trim($sql1)) or die("É possível que a ordem das colunas da planilha esteja incorreta.");


					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA
					SET TIP_FAIXEXT=UPPER(TRIM(TIP_FAIXEXT))");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Código externo não preenchido!'
					WHERE TRIM(IFNULL(DES_PRODUTO,'')) = ''");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Qtd. Faixa não preenchida!'
					WHERE TRIM(IFNULL(QTD_FAIXEXT,'')) = ''");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Ganho não preenchido!'
					WHERE TRIM(IFNULL(TIP_FAIXEXT,'')) = ''");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Limite de Uso não preenchido!'
					WHERE TRIM(IFNULL(QTD_FAIXLIM, 0)) = 0");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Cód. Externo não preenchido!'
					WHERE TRIM(IFNULL(COD_EXTERNO,'')) = ''");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Produto não encontrado na base!'
					WHERE (SELECT COUNT(1) FROM PRODUTOCLIENTE WHERE COD_EXTERNO = IMPORT_PRODEXTRA.COD_EXTERNO AND PRODUTOCLIENTE.COD_EMPRESA = $cod_empresa) = 0");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA SET LOG_IMPORT='N', MSG_ERRO='Cód. Externo igual em mais de um produto na base!'
					WHERE COUNT(SELECT PRD.COD_PRODUTO FROM PRODUTOCLIENTE PRD WHERE IPE.COD_EXTERNO = PRD.COD_EXTERNO 
						AND COD_EMPRESA = $cod_empresa) > 1");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA IPE 
					INNER JOIN PRODUTOCLIENTE PRD ON PRD.COD_EXTERNO = IPE.COD_EXTERNO AND PRD.COD_EMPRESA = $cod_empresa
					SET IPE.COD_PRODUTO = PRD.COD_PRODUTO
					WHERE IPE.COD_EMPRESA = $cod_empresa
					AND IPE.LOG_IMPORT = 'S'");

					mysqli_query(connTemp($cod_empresa, ""), "UPDATE IMPORT_PRODEXTRA
					INNER JOIN VANTAGEMEXTRAFAIXA ON IMPORT_PRODEXTRA.COD_PRODUTO = VANTAGEMEXTRAFAIXA.COD_PRODUTO
					SET LOG_IMPORT='N', MSG_ERRO='Produto específico já cadastrado!'																 
					WHERE VANTAGEMEXTRAFAIXA.COD_CAMPANHA = $cod_campanha 
					AND VANTAGEMEXTRAFAIXA.COD_EMPRESA = $cod_empresa");

					// exit();
					unset($sql1);

					sleep(5);
				}
				$reader->close();
			} else {

				echo 'Arquivo infectado por: <i>' . $retorno['MSG'] . '</i>';
			}
		}

		break;

	case "ler": //Rotina de leitura da prévia dos dados enviados


?>

		<div class="row">

			<div class="push50"></div>

			<div class="col-md-3"></div>

			<div class="col-md-6">

				<div class="col-md-4">
					<div class="form-group">
						<label for="inputName" class="control-label">Nome e Tipo do Arquivo</label>
						<input type="text" class="form-control input-sm leitura2" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" readonly>
					</div>
				</div>

				<?php

				$sqlLinhas = "SELECT COUNT(IMPORT_PRODEXTRA.COD_PRODIMPORT) AS QTD, COUNT(VANTAGEMEXTRAFAIXA.COD_PRODUTO) QTD_EXIST,
				SUM(IF(IMPORT_PRODEXTRA.LOG_IMPORT='S',1,0)) - COUNT(VANTAGEMEXTRAFAIXA.COD_PRODUTO) AS QTD_LINHAS_NOVOS,
				SUM(IF(IMPORT_PRODEXTRA.LOG_IMPORT='N',1,0)) AS QTD_LINHAS_ERRO
				FROM IMPORT_PRODEXTRA
				LEFT JOIN PRODUTOCLIENTE ON (PRODUTOCLIENTE.COD_PRODUTO=IMPORT_PRODEXTRA.COD_PRODUTO)
				LEFT JOIN VANTAGEMEXTRAFAIXA ON (VANTAGEMEXTRAFAIXA.COD_PRODUTO=IMPORT_PRODEXTRA.COD_PRODUTO 
					AND VANTAGEMEXTRAFAIXA.COD_CAMPANHA = $cod_campanha)
				WHERE IMPORT_PRODEXTRA.COD_EMPRESA = $cod_empresa";
				// fnEscreve($sqlLinhas);
				// exit();
				$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlLinhas));
				$qrLinhas = mysqli_fetch_assoc($result);
				$qrLinhas['LINHAS'] = $qrLinhas['QTD'];

				$qrLinhas['QTD_LINHAS_NOVOS'] = ($qrLinhas['QTD_LINHAS_NOVOS'] < 0 ? 0 : $qrLinhas['QTD_LINHAS_NOVOS']);
				?>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Qtd. Linhas</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?= $qrLinhas['QTD']; ?>" readonly>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Qtd. Exist.</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS_EXISTS" id="QTD_LINHAS_EXISTS" maxlength="45" value="<?= $qrLinhas['QTD_EXIST']; ?>" readonly>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Qtd. Novos</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS_NOVOS" id="QTD_LINHAS_NOVOS" maxlength="45" value="<?= $qrLinhas['QTD_LINHAS_NOVOS']; ?>" readonly>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Qtd. Inv&aacute;lidos</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS_ERRO" id="QTD_LINHAS_ERRO" maxlength="45" value="<?= $qrLinhas['QTD_LINHAS_ERRO']; ?>" readonly>
					</div>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="col-md-10 col-md-offset-1">
				<div class="collapse-chevron">
					<a data-toggle="collapse" class="col-md-12 collapsed btn btn-sm btn-default" href="#collapseFilter">
						<span class="fa fa-chevron-down" aria-hidden="true"></span>&nbsp;
						Visualizar Prévia
					</a>
				</div>

				<div class="collapse" id="collapseFilter">

					<table class="table">
						<thead>
							<tr>
								<th>Produto</th>
								<th>C&oacute;d. Externo</th>
								<th>Qtd. Extra</th>
								<th width="20%">Ganha</th>
								<th>Limite de Uso</th>
								<th>Limite Qtd. Produto</th>
								<th>Msg. Erro</th>
							</tr>
						</thead>

						<tbody id="relConteudo">

							<?php

							$sql = "SELECT NOM_TPCAMPA FROM WEBTOOLS.TIPOCAMPANHA where COD_TPCAMPA = (SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "')";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
							$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

							if (isset($arrayQuery)) {
								$tip_campanha = $qrBuscaTpCampanha['NOM_TPCAMPA'];
							} else {
								$tip_campanha = "";
							}

							$sqlProd = "
								SELECT IPE.*, PRD.DES_PRODUTO FROM IMPORT_PRODEXTRA IPE
								LEFT JOIN PRODUTOCLIENTE PRD ON PRD.COD_PRODUTO = IPE.COD_PRODUTO
								WHERE IPE.COD_EMPRESA = $cod_empresa
								ORDER BY PRD.DES_PRODUTO
								$limit
								";

							$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlProd));
							////fnEscreve($qrLinhas['LINHAS']);

							while ($qrProd = mysqli_fetch_assoc($result)) {

								switch ($qrProd['TIP_FAIXEXT']) {
									case 'PCT':
										$tipo = "% venda";
										break;

									case 'PCP':
										$tipo = "% produto";
										break;

									case 'ABS':
										$tipo = $tip_campanha;
										break;

									case 'ABP':
										$tipo = "% Qtd. produto";
										break;

									default:
										$tipo = "";
										break;
								}

							?>
								<tr>
									<td><?php echo $qrProd['DES_PRODUTO']; ?></td>
									<td><?php echo $qrProd['COD_EXTERNO']; ?></td>
									<td><?php echo fnValor($qrProd['QTD_FAIXEXT'], 0); ?></td>
									<td><?php echo $tipo; ?></td>
									<td><?php echo $qrProd['QTD_FAIXLIM']; ?></td>
									<td><?php echo $qrProd['QTD_LIMITPRODU']; ?></td>
									<td><?php echo $qrProd['MSG_ERRO']; ?></td>
								</tr>
							<?php
							}
							?>

						</tbody>

					</table>

					<?php
					if ($qrLinhas['LINHAS'] > 20) { ?>
						<a class="btn btn-primary col-md-12" type="button" id="loadMore">Carregar Mais</a>
					<?php } ?>

				</div>

			</div>

		</div>

		<div class="push100"></div>

		<hr>

		<div class="col-md-2">
			<button type="submit" class="col-md-12 btn btn-primary prev1"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
		</div>

		<div class="col-md-8"></div>

		<div class="col-md-2">
			<button type="submit" class="col-md-12 btn btn-primary next2">Próximo<i class="fas fa-arrow-right pull-right"></i></button>
		</div>


		<div class="push10"></div>

		<script>
			$.ajax({
				type: "GET",
				url: "../uploads/uploadImportProdExtra.php?acao=confirmar&id=<?php echo $cod_empresa; ?>&idc=<?= fnEncode($cod_campanha) ?>",
				success: function(data) {
					$("#passo3").html(data);
				},
				error: function() {
					alert('Erro ao carregar...');
				}
			});

			var cont = 0;
			$('#loadMore').click(function() {

				cont += 10;

				if (cont >= "<?php echo $qrLinhas['LINHAS']; ?>") {
					$('#loadMore').addClass('disabled');
					$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
				}

				$.ajax({
					type: "GET",
					url: "../uploads/uploadImportProdExtra.php?acao=loadMore&itens=" + cont + "&id=<?php echo $cod_empresa; ?>&idc=<?= fnEncode($cod_campanha) ?>",
					beforeSend: function() {
						$('#loadMore').text('Carregando...');
					},
					success: function(data) {
						$('#loadMore').text('Carregar Mais');
						$('#relConteudo').append(data);
					},
					error: function() {
						alert('Erro ao carregar...');
					}
				});
			});

			$('.prev1').click(function() {
				$('#passo2').hide();
				$('#passo1').show();
				$("#step2 div.fundo, #step2 a.btn").removeClass('fundoAtivo');
			});

			$('.next2').click(function() {
				$('#passo2').hide();
				$('#passo3').show();
				$("#step3 div.fundo, #step3 a.btn").addClass('fundoAtivo');
			});
		</script>

	<?php

		break;

	case "confirmar": //Rotina de confirmação dos dados enviados

	?>

		<div class="push100"></div>

		<div class="row">

			<div class="col-md-8 col-md-offset-2 text-center">
				<h4><b>Deseja importar os produtos válidos da lista?</b></h4>
			</div>

		</div>

		<div class="push100"></div>
		<div class="push20"></div>

		<hr>

		<div class="col-md-2">
			<button class="col-md-12 btn btn-primary prev2"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
		</div>

		<div class="col-md-8"></div>

		<div class="col-md-2">
			<button class="col-md-12 btn btn-primary next3">Confirmar<i class="fas fa-check pull-right"></i></button>
		</div>


		<div class="push10"></div>

		<script>
			$('.next3').click(function() {

				$.ajax({
					type: "POST",
					url: "../uploads/uploadImportProdExtra.php?id=<?php echo $cod_empresa; ?>&idc=<?= fnEncode($cod_campanha) ?>",
					data: $('#formulario').serialize(),
					beforeSend: function() {
						$('#passo3').hide();
						$('#passo4').show();
						$("#passo4").html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						console.log(data);
						$("#passo4").html(data);
						$("#step4 div.fundo, #step4 a.btn").addClass('fundoAtivo');
					},
					error: function() {
						alert('Erro ao carregar...');
					}
				});

			});

			$('.prev2').click(function() {
				$('#passo3').hide();
				$('#passo2').show();
				$("#step3 div.fundo, #step3 a.btn").removeClass('fundoAtivo');
			});
		</script>

		<?php

		break;

	case "loadMore":

		$limite = $_GET['itens'];

		////fnEscreve($limite);

		$sql = "SELECT NOM_TPCAMPA FROM WEBTOOLS.TIPOCAMPANHA where COD_TPCAMPA = (SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "')";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			$tip_campanha = $qrBuscaTpCampanha['NOM_TPCAMPA'];
		} else {
			$tip_campanha = "";
		}

		$sqlProd = "
			SELECT IPE.*, PRD.DES_PRODUTO FROM IMPORT_PRODEXTRA IPE
			LEFT JOIN PRODUTOCLIENTE PRD ON PRD.COD_PRODUTO = IPE.COD_PRODUTO
			WHERE IPE.COD_EMPRESA = $cod_empresa
			ORDER BY PRD.DES_PRODUTO
			LIMIT 20
			";

		$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlProd));
		////fnEscreve($qrLinhas['LINHAS']);

		while ($qrProd = mysqli_fetch_assoc($result)) {

			switch ($qrProd['TIP_FAIXEXT']) {
				case 'PCT':
					$tipo = "% venda";
					break;

				case 'PCP':
					$tipo = "% produto";
					break;

				case 'ABS':
					$tipo = $tip_campanha;
					break;

				case 'ABP':
					$tipo = "% Qtd. produto";
					break;

				default:
					$tipo = "";
					break;
			}

		?>
			<tr>
				<td><?php echo $qrProd['DES_PRODUTO']; ?></td>
				<td><?php echo $qrProd['COD_EXTERNO']; ?></td>
				<td><?php echo fnValor($qrProd['QTD_FAIXEXT'], 2); ?></td>
				<td><?php echo $tipo; ?></td>
				<td><?php echo $qrProd['QTD_FAIXLIM']; ?></td>
				<td><?php echo $qrProd['QTD_LIMITPRODU']; ?></td>
				<td><?php echo $qrProd['MSG_ERRO']; ?></td>
			</tr>
		<?php
		}
		?>


	<?php

		break;

	default:

		//busca dados da regra extra (tela) 
		$sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$tem_extra = mysqli_num_rows($arrayQuery);

		if ($tem_extra == 0) {

			$sqlExtra = "INSERT INTO VANTAGEMEXTRA(
					COD_CAMPANHA, 
					COD_USUCADA, 
					COD_EMPRESA,
					DAT_CADASTR
					) VALUES(
					$cod_campanha,
					$cod_usucada,
					$cod_empresa,
					NOW()
				)";

			mysqli_query(connTemp($cod_empresa, ''), $sqlExtra);
		}


		$sqlImport = "SELECT * FROM IMPORT_PRODEXTRA
				WHERE COD_EMPRESA = $cod_empresa 
				AND LOG_IMPORT = 'S'";

		$arrayExtra = mysqli_query(connTemp($cod_empresa, ''), $sqlImport);

		$tip_faixas = "PRD";
		$val_faixini = 0;
		$val_faixfim = 0;
		$cod_formapa = 0;
		$cod_geral = 0;
		$qtd_limitprodu = 0;

		$sqlVantagem = "";
		$countProd = 0;

		while ($qrExtra = mysqli_fetch_assoc($arrayExtra)) {

			$des_produto = $qrExtra['DES_PRODUTO'];
			$cod_externo = $qrExtra['COD_EXTERNO'];
			$qtd_faixext = fnValor($qrExtra['QTD_FAIXEXT'], 2);
			$tip_faixext = $qrExtra['TIP_FAIXEXT'];
			$qtd_faixlim = $qrExtra['QTD_FAIXLIM'];
			$qtd_limitprodu = $qrExtra['QTD_LIMITPRODU'];
			$tip_calculo = $qrExtra['TIP_CALCULO'];
			$cod_produto = $qrExtra['COD_PRODUTO'];
			$opcao = "CAD";

			$sqlVantagem = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
					0,
					'" . $cod_campanha . "', 
					'" . $cod_empresa . "', 
					'" . $cod_geral . "', 
					'" . $tip_faixas . "', 
					'" . $val_faixini . "',
					'" . $val_faixfim . "',
					'" . fnValorSql($qtd_faixext) . "',
					'" . $tip_faixext . "',
					'" . $qtd_faixlim . "',
					'" . $cod_produto . "',
					'" . $cod_formapa . "',
					'" . $cod_usucada . "', 
					'" . $tip_calculo . "', 
					'" . $qtd_limitprodu . "', 
					'" . $opcao . "'    
				); ";

			mysqli_query(connTemp($cod_empresa, ""), $sqlVantagem);

			$countProd++;
		}

		$sql = "SELECT QTD_TOTPRODU FROM VANTAGEMEXTRA WHERE COD_CAMPANHA =" . $cod_campanha;
		$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

		if ($qrResult = mysqli_fetch_assoc($query)) {
			$totProdu = $countProd + $qrResult['QTD_TOTPRODU'];
		} else {
			$totProdu = $countProd;
		}


		$sql = "update VANTAGEMEXTRA set QTD_TOTPRODU = " . $totProdu . " where cod_campanha = " . $cod_campanha . " ";
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		if ($sqlVantagem != "") {

			sleep(5);
		}
		// exit();

	?>

		<div class="push100"></div>

		<div class="row">

			<div class="col-md-8 col-md-offset-2 text-center">
				<?php if ($countProd > 0) { ?>
					<h4><b><?= fnValor($countProd, 0) ?> produtos importados com sucesso!</b></h4>
				<?php } else { ?>
					<h4><b>Nenhum produto foi importado.</b></h4>
				<?php } ?>
			</div>

		</div>

		<div class="push100"></div>

		<hr>

		<div class="col-md-10"></div>

		<div class="col-md-2">
			<a href="action.do?mod=<?php echo fnEncode(1187) . "&id=" . fnEncode($cod_empresa) . "&idc=" . fnEncode($cod_campanha); ?>" class="col-md-12 btn btn-success concluir" target="_parent">Concluir</a>
		</div>


		<div class="push10"></div>

		<script>

		</script>

<?php

		break;
}





?>