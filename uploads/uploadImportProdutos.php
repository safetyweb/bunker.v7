<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';
require_once '../_system/convertxlsxtocsv.php';

//echo fnDebug('true');
////fnEscreve('Entra no ajax');
$sql1 = "";
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$cod_empresa = fnLimpaCampoZero($_GET['id']);
if (isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

switch ($acao) {

	case "gravar": //Rotina de gravação da planilha na tabela 'temporária'


		$sql = "DELETE FROM IMPORT_PRODUTOS WHERE COD_EMPRESA = $cod_empresa";
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
				// Define o diretório de destino para mover o arquivo
				$destinationDir = '/srv/www/htdocs/tmp';
				if (!file_exists($destinationDir)) {
					mkdir($destinationDir, 0755, true);
				}
				// Define o caminho completo com o nome original (para que a extensão seja preservada)
				$destinationFile = rtrim($destinationDir, DIRECTORY_SEPARATOR) . '/' . $file_name;

				// Move o arquivo do diretório temporário para o diretório de destino
				if (move_uploaded_file($file_tmp, $destinationFile)) {
					$jsonResponse = importProdSystem($destinationFile, ';', $destinationDir);
					$decode = json_decode($jsonResponse, true);
					$caminho_arquivo = $decode['data']['caminho_csv'];
				}


				$duplicado = 0;
				$ultimo_cod = 0;
				$insert = "";
				$countInsert = 0;
				$temRegistro = false;

				/*
				Glossário do array da planilha:

				$row[0] = COD_EXTERNO;
				$row[1] = EAN;
				$row[2] = PBM;
				$row[3] = DES_PRODUTO;
				$row[4] = CUSTO;
				$row[5] = PRECO;
				$row[6] = CATEGORIA;
				$row[7] = COD_EXT_CATEGORIA;
				$row[8] = SUBCATEGORIA;
				$row[9] = COD_EXT_SUBCAT;
				$row[10] = FORNECEDOR;
				$row[11] = COD_EXT_FORN;
				$row[12] = LOG_PONTUAR;
				*/

				if (($handle = fopen($caminho_arquivo, 'r')) !== false) {
					$contador = 0;
					$qtdLinhas = 0;
					$colunas = [];

					// Lê linha por linha
					while (($row = fgetcsv($handle, 1000, ';')) !== false) {
						// Formata as datas, se for o caso
						foreach ($row as $k => $r) {
							if (is_object($r)) {
								$row[$k] = $r->format('d/m/Y');
							} else {
								$row[$k] = $r;
							}
						}
						$codigosExternosBanco = [];
						// Pula a primeira linha (cabeçalho)
						if ($contador == 0) {
							$colunas = array_filter($row, function ($a) {
								return preg_match("#\S#", $a);
							});
						} else if ($contador != 0 && count($colunas) == 13) {

							// Buscando string SQL pelo código externo do produto
							if (in_array($row[0], $codigosExternosBanco)) {
								// fnEscreve(trim($row[0]));
								// Incrementando o contador caso o código externo seja duplicado (para informar o número de registros duplicados)
								$duplicado++;
							} else {
								$codigosExternosBanco[] = $row[0];
								// Comparando o último código externo com o código externo a ser gravado
								if (fnLimpaCampo(trim($row[0])) != $ultimo_cod && fnLimpaCampo(trim($row[0])) != "") {
									// Limitando o nome do produto a 250 caracteres (limite definido no campo da tabela)
									$ultimo_cod = fnLimpaCampo(trim($row[0]));
									$prod = fnLimpaCampo(trim($row[3]));
									$prod = substr("$prod", 0, 249);
									$prod = str_replace("'", "´", str_replace('"', "´", $prod));
									$custo = fnLimpaCampo(fnValorSql(fnValor($row[4], 2)));
									$preco = fnLimpaCampo(fnValorSql(fnValor($row[5], 2)));
									$sqlCat = "";

									$cod_extcat = fnLimpaCampo(@$row[7]);
									if ($cod_extcat == "") {
										$cod_extcat = 0;
									}

									$cod_subexte = fnLimpacampo(@$row[9]);
									if ($cod_subexte == "") {
										$cod_subexte = 0;
									}

									$cod_extforn = fnLimpacampo(@$row[11]);
									if ($cod_extforn == "") {
										$cod_extforn = 0;
									}

									$cat = fnLimpaCampo(trim(str_replace("'", "´", str_replace('"', "&quot;", @$row[6]))));
									$sub = fnLimpaCampo(trim(str_replace("'", "´", str_replace('"', "&quot;", @$row[8]))));
									$forn = fnLimpaCampo(trim(str_replace("'", "´", str_replace('"', "&quot;", @$row[10]))));
									$forn = preg_replace("/[^0-9A-Za-z&#; ]+/", "", trim($forn));

									$cod_categoria = 0;
									$cod_subcate = 0;
									$cod_fornecedor = 0;

									$insert .= "(
										'" . fnLimpaCampo(trim($row[0])) . "',
										'$cod_empresa',
										'" . fnLimpaCampo(trim($row[1])) . "',
										'$prod',
										'$cat',
										'$cod_categoria',
										'$cod_extcat',
										'$sub',
										'$cod_subcate',
										'$cod_subexte',
										'$forn',
										'$cod_fornecedor',
										'$cod_extforn',
										'$cod_usucada',
										'$custo',
										'$preco',
										'S',
										'" . fnLimpaCampo(trim($row[2])) . "',
										'" . fnLimpaCampoZero(trim($row[12])) . "'
									),";
									$countInsert++;
									// fnEscreve($countInsert);
									$temRegistro = true;
									if ($countInsert == 1000) {
										// fnEscreve("entrou no insert");

										$insert = rtrim(trim($insert), ',');

										$sql1 = "INSERT INTO IMPORT_PRODUTOS(
											COD_EXTERNO,
											COD_EMPRESA,
											EAN,
											DES_PRODUTO,
											DES_CATEGOR,
											COD_CATEGOR,
											COD_EXTCAT,
											DES_SUBCATE,
											COD_SUBCATE,
											COD_SUBEXTE,
											NOM_FORNECEDOR,
											COD_FORNECEDOR,
											COD_EXTFORN,
											COD_USUCADA,
											VAL_CUSTO,
											VAL_PRECO,
											LOG_IMPORT,
											LOG_PBM,
											LOG_ATIVO
											) VALUES $insert";

										mysqli_query(connTemp($cod_empresa, ""), trim($sql1));
										// fnTesteSql(connTemp($cod_empresa, ""), trim($sql1));
										// fnEscreve($sql1);
										$countInsert = 0;
										$insert = "";
										$qtdLinhas++;
									}
								} else {
									// Incrementa o contador caso o código externo seja duplicado
									if ($row[0]) {
										$ultimo_cod = fnLimpaCampo(trim($row[0]));
										$duplicado++;
									}
								}
							}
						} else {
							echo 'A planilha deve conter exatamente 13 colunas. Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
					fclose($handle); // Fecha o arquivo CSV após a leitura
				}


				if ($insert != "") {

					$stringInsert = rtrim(trim($insert), ',');

					$sql1 = "INSERT INTO IMPORT_PRODUTOS(
										COD_EXTERNO,
										COD_EMPRESA,
										EAN,
										DES_PRODUTO,
										DES_CATEGOR,
										COD_CATEGOR,
										COD_EXTCAT,
										DES_SUBCATE,
										COD_SUBCATE,
										COD_SUBEXTE,
										NOM_FORNECEDOR,
										COD_FORNECEDOR,
										COD_EXTFORN,
										COD_USUCADA,
										VAL_CUSTO,
										VAL_PRECO,
										LOG_IMPORT,
										LOG_PBM,
										LOG_ATIVO
										) VALUES $stringInsert";
					// echo $sql1 . "<br><br>";
					// exit();
					// fnEscreve($sql1);
					// fnEscreve($sql1);
					// $qtdLinhas++;
					mysqli_query(connTemp($cod_empresa, ""), trim($sql1));
					// fnTesteSql(connTemp($cod_empresa, ""), trim($sql1));
				}

				if ($temRegistro) {

					$sqlUpdt = "UPDATE IMPORT_PRODUTOS IP
								INNER JOIN CATEGORIA CT ON CT.COD_EXTERNO = IP.COD_EXTCAT AND CT.COD_EMPRESA = $cod_empresa AND CT.COD_EXTERNO != 0
								INNER JOIN SUBCATEGORIA SC ON SC.COD_SUBEXTE = IP.COD_SUBEXTE AND SC.COD_EMPRESA = $cod_empresa AND SC.COD_SUBEXTE != 0
								INNER JOIN FORNECEDORMRKA FMK ON FMK.COD_EXTERNO = IP.COD_EXTFORN AND FMK.COD_EMPRESA = $cod_empresa AND FMK.COD_EXTERNO != 0
								SET 
									IP.COD_CATEGOR = CT.COD_CATEGOR,
									IP.COD_SUBCATE = SC.COD_SUBCATE,
									IP.COD_FORNECEDOR = FMK.COD_FORNECEDOR
								WHERE IP.COD_EMPRESA = $cod_empresa
								";
					mysqli_query(connTemp($cod_empresa, ""), trim($sqlUpdt));

					echo ($duplicado);
					// sleep(5);
				}
				// $reader->close();
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

				<div class="col-md-7">
					<div class="form-group">
						<label for="inputName" class="control-label">Nome e Tipo do Arquivo</label>
						<input type="text" class="form-control input-sm leitura2" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" readonly>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Qtde de Linhas</label>
						<?php
						$sqlLinhas = "SELECT COUNT(COD_PRODUTO) AS LINHAS FROM IMPORT_PRODUTOS WHERE COD_EMPRESA = $cod_empresa";
						$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlLinhas));
						$qrLinhas = mysqli_fetch_assoc($result);
						?>
						<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?= $qrLinhas['LINHAS']; ?>" readonly>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="inputName" class="control-label">Linhas Duplicadas</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_DUPLICADOS" id="QTD_DUPLICADOS" maxlength="45" value="" readonly>
					</div>
				</div>

			</div>

		</div>

		<div class="row">

			<div class="col-md-3"></div>
			<div class="col-md-6">
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
								<th>Existe</th>
								<th>Cod. Externo</th>
								<th>Descrição do Produto</th>
							</tr>
						</thead>

						<tbody id="relConteudo">

							<?php

							$sqlProd = "
									  	 SELECT  IP.*,
										(SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IP.COD_EXTERNO AND 
																					   PC.COD_EMPRESA = IP.COD_EMPRESA)
										AS TEMPRODUTO
										FROM IMPORT_PRODUTOS IP
										left join PRODUTOCLIENTE PC on PC.COD_EXTERNO = IP.COD_EXTERNO AND 
																	    PC.COD_EMPRESA = IP.COD_EMPRESA
										WHERE IP.COD_EMPRESA = $cod_empresa
										ORDER BY DES_PRODUTO
									    LIMIT 20
									  ";

							$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlProd));
							////fnEscreve($qrLinhas['LINHAS']);

							while ($qrProd = mysqli_fetch_assoc($result)) {

								if ($qrProd['TEMPRODUTO'] == 1) $icone = '<span class="fas fa-check" style="color: #7cfc00;"></span>';
								else $icone = '<span class="fas fa-times" style="color: #e32636";></span>';

							?>
								<tr>
									<td class="text-center"><?php echo $icone; ?></td>
									<td><?php echo $qrProd['COD_EXTERNO']; ?></td>
									<td><?php echo $qrProd['DES_PRODUTO']; ?></td>
								</tr>
							<?php
							}
							?>

						</tbody>

					</table>

					<?php
					if ($qrLinhas['LINHAS'] > 20) { ?>
						<a class="btn btn-primary col-md-12" type="button" id="loadMore">Carregar Mais Produtos Da Lista</a>
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
				url: "../uploads/uploadImportProdutos.php?acao=confirmar&id=<?php echo $cod_empresa; ?>",
				success: function(data) {
					$("#passo3").html(data);
				},
				error: function() {
					alert('Erro ao carregar...');
				}
			});

			var cont = 0;
			$('#loadMore').click(function() {

				cont += 20;

				if (cont >= "<?php echo $qrLinhas['LINHAS']; ?>") {
					$('#loadMore').addClass('disabled');
					$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
				}

				$.ajax({
					type: "GET",
					url: "../uploads/uploadImportProdutos.php?acao=loadMore&itens=" + cont + "&id=<?php echo $cod_empresa; ?>",
					beforeSend: function() {
						$('#loadMore').text('Carregando...');
					},
					success: function(data) {
						$('#loadMore').text('Carregar Mais Produtos Da Lista');
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

		<div class="push50"></div>

		<div class="row">


			<div class="col-md-4"></div>

			<div class="col-md-4 text-center">
				<h4><b>O que deseja fazer?</b></h4>
			</div>

		</div>

		<div class="row text-center">

			<div class="col-md-4"></div>

			<div class="col-md-2">
				<input type="radio" id="ATUALIZAR" name="RADIO" checked value="ATUALIZAR">
				<label for="ATUALIZAR">Inserir somente novos produtos na lista de produtos</label>
			</div>

			<div class="col-md-2">
				<input type="radio" id="SUBSTITUIR" name="RADIO" value="SUBSTITUIR">
				<label for="SUBSTITUIR">Inserir produtos não existentes e atualizar produtos já existentes de mesmo nome na lista de produtos</label>
			</div>

		</div>

		<div class="push100"></div>

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
					url: "../uploads/uploadImportProdutos.php?id=<?php echo $cod_empresa; ?>",
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

	?>

		<?php

		$limite = $_GET['itens'];

		////fnEscreve($limite);


		$sqlProd = "
						  	 SELECT  IP.*,
							(SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IP.COD_EXTERNO AND 
																		   PC.COD_EMPRESA = IP.COD_EMPRESA)
							AS TEMPRODUTO
							FROM IMPORT_PRODUTOS IP
							left join PRODUTOCLIENTE PC on PC.COD_EXTERNO = IP.COD_EXTERNO AND 
														    PC.COD_EMPRESA = IP.COD_EMPRESA
							WHERE IP.COD_EMPRESA = $cod_empresa
							ORDER BY DES_PRODUTO
						    LIMIT $limite,20
						 ";

		$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlProd));

		while ($qrProd = mysqli_fetch_assoc($result)) {

			if ($qrProd['TEMPRODUTO'] == 1) $icone = '<span class="fas fa-check" style="color: #7cfc00;"></span>';
			else $icone = '<span class="fas fa-times" style="color: #e32636";></span>';

		?>
			<tr>
				<td class="text-center"><?php echo $icone; ?></td>
				<td><?php echo $qrProd['COD_EXTERNO']; ?></td>
				<td><?php echo $qrProd['DES_PRODUTO']; ?></td>
			</tr>
		<?php
		}
		?>


		<?php

		break;

	default:

		//rotinas de iserção e substituição de produtos da importação

		if (isset($_POST['RADIO'])) {

			$escolha = $_POST['RADIO'];

			$jaexiste = 0;
			$altera = 0;

			$sqlCategorias = "INSERT INTO categoria (COD_EMPRESA, COD_EXTERNO, DES_CATEGOR, COD_USUCADA, DAT_CADASTR) 
				SELECT 
					$cod_empresa AS COD_EMPRESA, 
					CASE 
						WHEN i.COD_EXTCAT IS NULL THEN FLOOR(RAND() * 8001) + 2000 
						ELSE i.COD_EXTCAT 
					END AS COD_EXTERNO,
					i.DES_CATEGOR,
					$cod_usucada AS COD_USUCADA,
					NOW()
				FROM import_produtos i
				LEFT JOIN categoria c 
					ON c.cod_empresa = $cod_empresa
				AND c.DES_CATEGOR = i.DES_CATEGOR
				WHERE c.DES_CATEGOR IS NULL
				AND i.DES_CATEGOR != ''
				GROUP BY i.DES_CATEGOR;

				UPDATE categoria c
				JOIN import_produtos i 
					ON i.COD_EXTCAT = c.COD_EXTERNO
				SET c.DES_CATEGOR = i.DES_CATEGOR
				WHERE c.cod_empresa = $cod_empresa
				AND c.DES_CATEGOR <> i.DES_CATEGOR
				AND i.DES_CATEGOR != ''
				AND i.COD_EXTCAT IS NOT NULL
				AND i.COD_EXTCAT != ''
				AND i.COD_EXTCAT != 0;";
			mysqli_multi_query(connTemp($cod_empresa, ""), trim($sqlCategorias));

			$sqlInsereSubcate = "INSERT INTO subcategoria (COD_EMPRESA, COD_SUBEXTE, DES_SUBCATE, COD_USUCADA, DAT_CADASTR, COD_CATEGOR)
				SELECT
					$cod_empresa AS COD_EMPRESA,
					CASE WHEN i.COD_SUBEXTE IS NULL THEN FLOOR(RAND() * 8001) + 2000 ELSE i.COD_SUBEXTE END AS COD_SUBEXTE,
					i.DES_SUBCATE,
					$cod_usucada AS COD_USUCADA,
					NOW() AS DAT_CADASTR,
					c.COD_CATEGOR
				FROM import_produtos i
				INNER JOIN categoria c 
					ON c.cod_empresa = $cod_empresa 
				AND c.DES_CATEGOR = i.DES_CATEGOR
				WHERE NOT EXISTS (
					SELECT 1
					FROM subcategoria s
					WHERE s.COD_EMPRESA = $cod_empresa  
					AND s.COD_CATEGOR = c.COD_CATEGOR
					AND s.DES_SUBCATE = i.DES_SUBCATE
					AND i.DES_SUBCATE != ''
				)
				AND i.DES_SUBCATE != ''
				AND i.COD_EMPRESA = $cod_empresa
				GROUP BY i.DES_CATEGOR, i.DES_SUBCATE;

			";
			mysqli_multi_query(connTemp($cod_empresa, ""), trim($sqlInsereSubcate));

			$sqlFornecedor = "INSERT INTO fornecedormrka (COD_EXTERNO, NOM_FORNECEDOR, COD_EMPRESA, COD_USUCADA, DAT_CADASTR)
					SELECT
						CASE 
						WHEN IMP.COD_EXTFORN IS NULL THEN FLOOR(RAND() * 8001) + 2000 
						ELSE IMP.COD_EXTFORN 
						END AS COD_EXTERNO,
						IMP.NOM_FORNECEDOR,
						IMP.COD_EMPRESA,
						$cod_usucada AS COD_USUCADA,
						NOW() AS DAT_CADASTR
					FROM import_produtos IMP
					WHERE IMP.COD_EMPRESA = $cod_empresa
					AND IMP.NOM_FORNECEDOR != ''
					AND IMP.NOM_FORNECEDOR IS NOT NULL
					AND NOT EXISTS (
						SELECT 1 
						FROM fornecedormrka f
						WHERE f.COD_EMPRESA = IMP.COD_EMPRESA
							AND f.COD_EXTERNO = IMP.COD_EXTFORN
					)
					AND NOT EXISTS (
						SELECT 1 
						FROM fornecedormrka f
						WHERE f.COD_EMPRESA = IMP.COD_EMPRESA
							AND f.NOM_FORNECEDOR = IMP.NOM_FORNECEDOR
					)
					GROUP BY IMP.NOM_FORNECEDOR;
					UPDATE fornecedormrka f
					JOIN import_produtos i ON i.COD_EXTFORN = f.COD_EXTERNO
					SET f.NOM_FORNECEDOR = i.NOM_FORNECEDOR
					WHERE f.COD_EMPRESA = $cod_empresa
					AND f.NOM_FORNECEDOR <> i.NOM_FORNECEDOR
					AND i.COD_EXTFORN != ''
					AND i.COD_EXTFORN != 0
					AND i.COD_EXTFORN IS NOT NULL;";
			mysqli_multi_query(connTemp($cod_empresa, ""), trim($sqlFornecedor));
			// sleep(2);

			$sqlUpdt = "UPDATE IMPORT_PRODUTOS ip
				LEFT JOIN (
					SELECT DES_CATEGOR, COD_CATEGOR
					FROM CATEGORIA
					WHERE COD_EMPRESA = $cod_empresa
					GROUP BY DES_CATEGOR
				) cat ON ip.DES_CATEGOR = cat.DES_CATEGOR
				LEFT JOIN (
					SELECT c.DES_CATEGOR, sc.DES_SUBCATE, sc.cod_subcate
					FROM SUBCATEGORIA sc
					JOIN CATEGORIA c ON c.COD_CATEGOR = sc.cod_categor
					WHERE c.COD_EMPRESA = $cod_empresa
					GROUP BY c.DES_CATEGOR, sc.DES_SUBCATE
				) sub ON ip.DES_CATEGOR = sub.DES_CATEGOR AND ip.DES_SUBCATE = sub.DES_SUBCATE
				LEFT JOIN (
					SELECT NOM_FORNECEDOR, COD_FORNECEDOR
					FROM FORNECEDORMRKA
					WHERE COD_EMPRESA = $cod_empresa
					GROUP BY NOM_FORNECEDOR
				) forn ON ip.NOM_FORNECEDOR = forn.NOM_FORNECEDOR
				SET 
					ip.COD_CATEGOR = cat.COD_CATEGOR,
					ip.COD_SUBCATE = sub.cod_subcate,
					ip.COD_FORNECEDOR = forn.COD_FORNECEDOR
				WHERE ip.COD_EMPRESA = $cod_empresa;";
			$result = mysqli_query(connTemp($cod_empresa, ""), $sqlUpdt);
			if (!$result) {
				// Se houve erro, exibe a mensagem do erro
				fnEscreve(mysqli_error(connTemp($cod_empresa, "")));
			}

			// UPDATE IMPORT_PRODUTOS IP
			// SET 
			//     IP.COD_CATEGOR = (
			//         SELECT COD_CATEGOR 
			//         FROM categoria 
			//         WHERE DES_CATEGOR = IP.DES_CATEGOR 
			//           AND COD_EMPRESA = 7
			//         ORDER BY 1 
			//         LIMIT 1
			//     ),
			//     IP.COD_SUBCATE = (
			//         SELECT cod_subcate 
			//         FROM SUBCATEGORIA 
			//         WHERE DES_SUBCATE = IP.DES_SUBCATE 
			//           AND cod_categor = (
			//               SELECT CT2.COD_CATEGOR 
			//               FROM CATEGORIA CT2
			//               JOIN SUBCATEGORIA SC2 ON CT2.COD_CATEGOR = SC2.COD_CATEGOR
			//               WHERE SC2.DES_SUBCATE = IP.DES_SUBCATE
			//                 AND CT2.DES_CATEGOR = IP.DES_CATEGOR 
			//                 AND CT2.COD_EMPRESA = 7
			//               ORDER BY 1 
			//               LIMIT 1
			//           )
			//         ORDER BY 1 
			//         LIMIT 1  -- Garantir que apenas um valor seja retornado
			//     ),
			//     IP.COD_FORNECEDOR = (
			//         SELECT COD_FORNECEDOR 
			//         FROM FORNECEDORMRKA 
			//         WHERE NOM_FORNECEDOR = IP.NOM_FORNECEDOR 
			//           AND COD_EMPRESA = 7
			//         ORDER BY 1 
			//         LIMIT 1  -- Garantir que apenas um valor seja retornado
			//     )
			// WHERE IP.COD_EMPRESA = 7;
			// fnTesteSql(connTemp($cod_empresa, ""), trim($sqlUpdt));



			sleep(2);

			// ------------------------------------------------------------------------------------------------------------------------------------- FIM DA CRIAÇÃO DOS ITENS 

			if ($escolha != "ATUALIZAR" && $escolha != "") {

				$altera = 1;

				$sqlUpdtProd = "UPDATE PRODUTOCLIENTE PC
									INNER JOIN IMPORT_PRODUTOS IP ON IP.COD_EXTERNO = PC.COD_EXTERNO AND PC.COD_EMPRESA = $cod_empresa
									SET
										PC.DES_PRODUTO = IP.DES_PRODUTO,
										PC.EAN = IP.EAN,
										PC.COD_CATEGOR = IP.COD_CATEGOR,
										PC.COD_SUBCATE = IP.COD_SUBCATE,
										PC.COD_FORNECEDOR = IP.COD_FORNECEDOR,
										PC.LOG_PRODPBM = IP.LOG_PBM,
										PC.COD_ALTERAC = IP.COD_USUCADA,
										PC.DAT_ALTERAC = NOW(),
										IP.LOG_IMPORT = 'N',
										PC.LOG_PONTUAR = IP.LOG_ATIVO
									WHERE PC.COD_EMPRESA = $cod_empresa
									AND IP.COD_EMPRESA = $cod_empresa";

				// fnEscreve($sqlUpdtProd);

			} else {

				$altera = 0;

				$sqlUpdtProd = "UPDATE IMPORT_PRODUTOS IP
								 INNER JOIN PRODUTOCLIENTE PC ON PC.COD_EXTERNO = IP.COD_EXTERNO AND IP.COD_EMPRESA = $cod_empresa
								 SET
										IP.LOG_IMPORT = 'N'
									WHERE PC.COD_EMPRESA = $cod_empresa
									AND IP.COD_EMPRESA = $cod_empresa";
			}

			mysqli_query(connTemp($cod_empresa, ""), trim($sqlUpdtProd));

			sleep(2);

			//calcula qtd de podutos no import
			$sqlLinhas = "SELECT COUNT(COD_PRODUTO) AS LINHAS FROM IMPORT_PRODUTOS WHERE COD_EMPRESA = $cod_empresa";
			$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlLinhas));
			$qrLinhas = mysqli_fetch_assoc($result);
			$qtd_prod = $qrLinhas['LINHAS'];

			//verifica se existe lote para empresa
			$sqlLote = "SELECT * FROM LOTE_IMPORTPROD WHERE COD_EMPRESA = $cod_empresa";
			$query = mysqli_query(connTemp($cod_empresa, ""), $sqlLote);


			if ($query->num_rows <= 0) {
				$sqlInsLot = "INSERT INTO LOTE_IMPORTPROD(
											COD_EMPRESA,
											QTD_PROD,
											COD_USUCADA,
											DAT_CADASTR
											)VALUES(
											$cod_empresa,
											$qtd_prod,
											$cod_usucada,
											NOW()
										)";
				mysqli_query(connTemp($cod_empresa, ''), $sqlInsLot);

				$sqlLote = "SELECT MAX(COD_LOTE) AS COD_LOTE FROM LOTE_IMPORTPROD WHERE COD_EMPRESA = $cod_empresa";
				$query = mysqli_query(connTemp($cod_empresa, ""), $sqlLote);
				$qrBuscaLote = mysqli_fetch_assoc($query);
				$cod_lote = $qrBuscaLote['COD_LOTE'];
			} else {

				while ($qrLote = mysqli_fetch_assoc($query)) {
					$cod_lote = $qrLote['COD_LOTE'];

					$sqlVerifica = "SELECT COUNT(*) AS num_registros FROM PRODUTOCLIENTE WHERE COD_EMPRESA = $cod_empresa AND COD_LOTE = $cod_lote";
					$queryVerifica = mysqli_query(connTemp($cod_empresa, ""), $sqlVerifica);
					$qrVerifica = mysqli_fetch_assoc($queryVerifica);
					$num_registros = $qrVerifica['num_registros'];

					if ($num_registros == 0) {
						$cod_lote = $qrLote['COD_LOTE'];
						break;
					} else {
						$cod_lote = "";
					}
				}

				if ($cod_lote == "") {

					$sqlInsLot = "INSERT INTO LOTE_IMPORTPROD(
													COD_EMPRESA,
													QTD_PROD,
													COD_USUCADA,
													DAT_CADASTR
													)VALUES(
													$cod_empresa,
													$qtd_prod,
													$cod_usucada,
													NOW()
													)";
					mysqli_query(connTemp($cod_empresa, ''), $sqlInsLot);

					$sqlLote = "SELECT MAX(COD_LOTE) AS COD_LOTE FROM LOTE_IMPORTPROD WHERE COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ""), $sqlLote);
					$qrBuscaLote = mysqli_fetch_assoc($query);
					$cod_lote = $qrBuscaLote['COD_LOTE'];
				}
			}

			$sqlInsProd = "INSERT INTO PRODUTOCLIENTE (
											COD_LOTE,
											COD_EXTERNO,
											COD_EMPRESA,
											DES_PRODUTO,
											EAN,
											COD_CATEGOR,
											COD_SUBCATE,
											COD_FORNECEDOR,
											LOG_PRODPBM,
											COD_USUCADA,
											DAT_CADASTR,
											LOG_PONTUAR
											)
							   SELECT
							   		$cod_lote,
							   		COD_EXTERNO,
									COD_EMPRESA,
									DES_PRODUTO,
									EAN,
									COD_CATEGOR,
									COD_SUBCATE,
									COD_FORNECEDOR,
									LOG_PBM,
									COD_USUCADA,
									DAT_CADASTR,
									LOG_ATIVO
							   FROM IMPORT_PRODUTOS IP 
							   WHERE IP.COD_EMPRESA = $cod_empresa
							   AND IP.LOG_IMPORT = 'S'";

			mysqli_query(connTemp($cod_empresa, ""), trim($sqlInsProd));


		?>

			<div class="push100"></div>

			<div class="row">

				<div class="col-md-4"></div>

				<?php

				$sqlLinhas = "SELECT COUNT(*) AS LINHAS FROM IMPORT_PRODUTOS WHERE COD_EMPRESA = $cod_empresa AND LOG_IMPORT = 'S'";
				$result = mysqli_query(connTemp($cod_empresa, ""), trim($sqlLinhas));
				$qrLinhas = mysqli_fetch_assoc($result);

				// comparando nro de linhas da planilha com nro de produtos existentes na lista e na blacklist

				if ($qrLinhas['LINHAS'] > 0) {
				?>

					<div class="col-md-4 text-center">
						<h4>Lista de produtos importada com <b>sucesso</b>!</h4>
						<h4>Código de lote gerado:<b><?= $cod_lote ?></b></h4>
					</div>

				<?php } else if ($qrLinhas['LINHAS'] == 0 && $altera == 0) { ?>

					<div class="col-md-4 text-center">
						<h4>Lista de produtos já existe. <b>Nenhum dado</b> foi alterado.</h4>
					</div>

				<?php } else { ?>

					<div class="col-md-4 text-center">
						<h4>Lista de produtos atualizada com <b>sucesso</b>!</h4>
						<h4>Código de lote gerado:<b><?= $cod_lote ?></b></h4>
					</div>

				<?php } ?>

			</div>

			<div class="push100"></div>

			<hr>

			<div class="col-md-10"></div>

			<div class="col-md-2">
				<a href="action.do?mod=<?php echo fnEncode(1321) . "&id=" . fnEncode($cod_empresa); ?>" class="col-md-12 btn btn-success concluir">Concluir</a>
			</div>


			<div class="push10"></div>

			<script>

			</script>

<?php

		}



		break;
}
?>