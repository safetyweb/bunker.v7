<?php

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	//echo fnDebug('true');
	////fnEscreve('Entra no ajax');

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	$cod_empresa = fnLimpaCampoZero($_GET['id']);
	if(isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

	////fnEscreve($cod_empresa);

	switch($acao){

		case "gravar": //Rotina de gravação da planilha na tabela 'temporária'

		$sql = "DELETE FROM IMPORT_PRODUTOS WHERE COD_EMPRESA = $cod_empresa";
				mysqli_query(connTemp($cod_empresa,""),trim($sql));

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

		    if($retorno['RESULTADO'] == 0){

				$reader = ReaderFactory::create(Type::XLSX); // for XLSX files

				$reader->open($file_tmp);
				
				$duplicado = 0;
				$ultimo_cod = 0;
				$insert = "";

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
				*/
				
				foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					
					foreach ($sheet->getRowIterator() as $row) {
						if($contador == 0){
							$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));
							////fnEscreve(count($colunas));
						}
						else if($contador != 0 && count($colunas) == 12){

							//buscando string sql pelo código externo do produto
							if (strpos($sql1, fnLimpaCampo(trim($row[0])))) {
								//incrementando o contador caso o cod externo seja duplicado (para informar o nro de registros duplicados)
							    $duplicado++;
							}else{

								//comparando o ultimo cod externo com o cod externo a ser gravado
								if(fnLimpaCampo(trim($row[0])) != $ultimo_cod && fnLimpaCampo(trim($row[0])) != ""){
									//limitando o nome do produto a 250 caracteres (limite definido no campo da tabela)
									$ultimo_cod = fnLimpaCampo(trim($row[0]));
									$prod = fnLimpaCampo(trim($row[3]));
									$prod = substr("$prod",0,249);
									$prod = str_replace("'","´",$prod);
									$custo = fnLimpaCampo(fnValorSql(fnValor($row[4],2)));
									$preco = fnLimpaCampo(fnValorSql(fnValor($row[5],2)));
									$sqlCat="";
									$cod_extcat = "$row[7]";
									$cod_subexte = "$row[9]";
									$cod_extforn = "$row[11]";


									// if($row[7] != "") { $selcodCat = "(SELECT COD_CATEGOR FROM CATEGORIA WHERE COD_EXTERNO = '$cod_categor' AND COD_EMPRESA = $cod_empresa) AS COD_CATEGOR";}
									// else {
									// 	if($row[6] != ""){
									// 		$selcodCat = "(SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR = '".fnLimpaCampo(trim(str_replace("'","´",$row[6])))."' AND COD_EMPRESA = $cod_empresa) AS COD_CATEGOR";
									// 	} else{
									// 		$selcodCat = "(0) AS COD_CATEGOR";
									// 	}
									// } 

									// if($row[9] != "") { $selcodSub = "(SELECT COD_SUBCATE FROM SUBCATEGORIA WHERE COD_SUBEXTE = '$cod_subcate' AND COD_EMPRESA = $cod_empresa) AS COD_SUBCATE"; }
									// else {
									// 	if($row[8] != ""){
									// 		$selcodSub = "(SELECT COD_SUBCATE FROM SUBCATEGORIA WHERE DES_SUBCATE = '".fnLimpaCampo(trim(str_replace("'","´",$row[8])))."' AND COD_EMPRESA = $cod_empresa) AS COD_SUBCATE";
									// 	} else{
									// 		$selcodSub = "(0) AS COD_SUBCATE";
									// 	}
									// }

									// if($row[11] != "") { $selcodForn = "(SELECT COD_FORNECEDOR FROM FORNECEDORMRKA WHERE COD_EXTERNO = '$cod_fornecedor' AND COD_EMPRESA = $cod_empresa) AS COD_FORNECEDOR"; }
									// else {
									// 	if($row[10] != ""){
									// 		$selcodForn = "(SELECT COD_FORNECEDOR FROM FORNECEDORMRKA WHERE NOM_FORNECEDOR = '".fnLimpaCampo(trim(str_replace("'","´",$row[10])))."' AND COD_EMPRESA = $cod_empresa) AS COD_FORNECEDOR";
									// 	} else{
									// 		$selcodForn = "(0) AS COD_FORNECEDOR";
									// 	}
									// }

									// $sqlCat = "SELECT $selcodCat,
									// 				  $selcodSub,
									// 				  $selcodForn;";

									// // fnEscreve($sqlCat);
									// // exit();
									

									// $arrayCat = mysqli_query(connTemp($cod_empresa,""),trim($sqlCat));
									// $cat = mysqli_fetch_assoc($arrayCat);

									// $cod_categoria = fnLimpaCampoZero($cat['COD_CATEGOR']);
									// $cod_subcate = fnLimpaCampoZero($cat['COD_SUBCATE']);
									// $cod_fornecedor = fnLimpaCampoZero($cat['COD_FORNECEDOR']);

									$insert .= "(
										'".fnLimpaCampo(trim($row[0]))."',
										'$cod_empresa',
										'".fnLimpaCampo(trim($row[1]))."',
										'$prod',
										'".fnLimpaCampo(trim(str_replace("'","´",$row[6])))."',
										'$cod_extcat',
										'".fnLimpaCampo(trim(str_replace("'","´",$row[8])))."',
										'$cod_subexte',
										'".fnLimpaCampo(trim(str_replace("'","´",$row[10])))."',
										'$cod_extforn',
										'$cod_usucada',
										'$custo',
										'$preco',
										'S',
										'".fnLimpaCampo(trim($row[2]))."'
										);";

								}else{
									//incrementando o contador caso o cod externo seja duplicado (para informar o nro de registros duplicados)
									if($row[0]){
										$ultimo_cod = fnLimpaCampo(trim($row[0]));
										$duplicado++;
									}
								}
							}
						}else{
							echo 'A planilha deve conter exatamente 12 colunas: "Código Externo", "EAN(os valores são opcionais)" e "Nome do Produto". Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
				}
				//fnEscreve($sql1);
				if($insert != ""){

					// $insert = rtrim($insert,',');

					$arrayInsert = explode(';', $insert);
					$qtd_total = count($arrayInsert);
					$stringInsert = "";
					$countRef = 99;

					for ($i=0; $i <= $qtd_total ; $i++) { 

						$stringInsert .= $arrayInsert[$i].",";

						if($i == $countRef){

							$stringInsert = rtrim(trim($stringInsert),',');

							$sql1 = "INSERT INTO IMPORT_PRODUTOS(
												COD_EXTERNO,
												COD_EMPRESA,
												EAN,
												DES_PRODUTO,
												DES_CATEGOR,
												COD_EXTCAT,
												DES_SUBCATE,
												COD_SUBEXTE,
												NOM_FORNECEDOR,
												COD_EXTFORN,
												COD_USUCADA,
												VAL_CUSTO,
												VAL_PRECO,
												LOG_IMPORT,
												LOG_PBM
												) VALUES $stringInsert";
							// fnEscreve($sql1);
							// exit();
							mysqli_multi_query(connTemp($cod_empresa,""),trim($sql1));
							// exit();

							$sql1 = "";

							$countRef += 100;
							$stringInsert = "";

						}

						# code...
					}

					if($stringInsert != ""){

						$sql1 = "INSERT INTO IMPORT_PRODUTOS(
											COD_EXTERNO,
											COD_EMPRESA,
											EAN,
											DES_PRODUTO,
											DES_CATEGOR,
											COD_EXTCAT,
											DES_SUBCATE,
											COD_SUBEXTE,
											NOM_FORNECEDOR,
											COD_EXTFORN,
											COD_USUCADA,
											VAL_CUSTO,
											VAL_PRECO,
											LOG_IMPORT,
											LOG_PBM
											) VALUES $stringInsert";
						// fnEscreve($sql1);
						// exit();
						mysqli_multi_query(connTemp($cod_empresa,""),trim($sql1));

					}

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
					mysqli_query(connTemp($cod_empresa,""),trim($sqlUpdt));

					echo($duplicado);
					// sleep(5);
				}
				$reader->close();

			}else{

		        echo 'Arquivo infectado por: <i>'.$retorno['MSG'].'</i>';

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
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
								$qrLinhas = mysqli_fetch_assoc($result);
							?>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?=$qrLinhas['LINHAS'];?>" readonly>
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

							$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));
							////fnEscreve($qrLinhas['LINHAS']);

							while($qrProd = mysqli_fetch_assoc($result)){

								if($qrProd['TEMPRODUTO'] == 1) $icone = '<span class="fas fa-check" style="color: #7cfc00;"></span>'; 
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
						if($qrLinhas['LINHAS'] > 20){ ?>
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
					success:function(data){
						$("#passo3").html(data);
					},
					error:function(){
						alert('Erro ao carregar...');
					}
				});

				var cont = 0;
				$('#loadMore').click(function(){
					
					cont +=20;

					if(cont >= "<?php echo $qrLinhas['LINHAS']; ?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
					}

					$.ajax({
						type: "GET",
						url: "../uploads/uploadImportProdutos.php?acao=loadMore&itens="+cont+"&id=<?php echo $cod_empresa; ?>",
						beforeSend:function(){	
							$('#loadMore').text('Carregando...');
						},
						success:function(data){
							$('#loadMore').text('Carregar Mais Produtos Da Lista');
							$('#relConteudo').append(data);
						},
						error:function(){
							alert('Erro ao carregar...');
						}
					});
				});

				$('.prev1').click(function(){
					$('#passo2').hide();
					$('#passo1').show();
					$("#step2 div.fundo, #step2 a.btn").removeClass('fundoAtivo');
				});

				$('.next2').click(function(){
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
					<label for="ATUALIZAR">Inserir produtos não-existentes na lista de produtos</label>
				</div>

				<div class="col-md-2">
					<input type="radio" id="SUBSTITUIR" name="RADIO" value="SUBSTITUIR">
					<label for="SUBSTITUIR">Inserir produtos não-existentes e substituir produtos em comum na lista de produtos</label>
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

			$('.next3').click(function(){
				$.ajax({
					type: "POST",
					url: "../uploads/uploadImportProdutos.php?id=<?php echo $cod_empresa; ?>",
					data: $('#formulario').serialize(),
					beforeSend:function(){
						$('#passo3').hide();
						$('#passo4').show();
						$("#passo4").html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						console.log(data);
						$("#passo4").html(data);
						$("#step4 div.fundo, #step4 a.btn").addClass('fundoAtivo');
					},
					error:function(){
						alert('Erro ao carregar...');
					}
				});
			});

			$('.prev2').click(function(){
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

				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));

				while($qrProd = mysqli_fetch_assoc($result)){

								if($qrProd['TEMPRODUTO'] == 1) $icone = '<span class="fas fa-check" style="color: #7cfc00;"></span>'; 
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

			if(isset($_POST['RADIO'])){

				$escolha = $_POST['RADIO'];

					$jaexiste=0;
					$altera=0;
					$sqlInsertCat = "";
					$sqlInsertSub = "";
					$sqlInsertForn = "";

				// CRIANDO CATEGORIAS, SUBCATEGORIAS E FORNECEDORES QUE AINDA NÃO EXISTEM NO SISTEMA ---------------------------------------------------------------

					$sqlImportCat = "SELECT DISTINCT IP.DES_CATEGOR, IP.COD_EXTCAT FROM IMPORT_PRODUTOS IP 
										WHERE (SELECT COUNT(DES_CATEGOR) FROM CATEGORIA WHERE DES_CATEGOR = IP.DES_CATEGOR AND COD_EMPRESA = $cod_empresa) = 0 
										AND IP.COD_EMPRESA = $cod_empresa";

					$arraySelCat = mysqli_query(connTemp($cod_empresa,""),trim($sqlImportCat));

					while($qrSelCat = mysqli_fetch_assoc($arraySelCat)){
						if(isset($qrSelCat) && $qrSelCat['DES_CATEGOR'] != ''){

							$cod_externo = $qrSelCat['COD_EXTCAT'];
							$des_categor = substr($qrSelCat['DES_CATEGOR'],0,19);

							$sqlInsertCat .= "INSERT INTO CATEGORIA(
															DES_CATEGOR,
															COD_EXTERNO,
															COD_EMPRESA
														) VALUES(
															'$des_categor',
															'$cod_externo',
															 $cod_empresa
														); ";

							// fnEscreve($sqlInsertSub);
							// exit();
						}
					}

					mysqli_multi_query(connTemp($cod_empresa,""),trim($sqlInsertCat));

					sleep(2);

					$sqlImportSub = "SELECT DISTINCT IP.DES_SUBCATE, IP.COD_SUBEXTE, IP.DES_CATEGOR FROM IMPORT_PRODUTOS IP 
										WHERE (SELECT COUNT(DES_SUBCATE) FROM SUBCATEGORIA WHERE DES_SUBCATE = IP.DES_SUBCATE AND COD_EMPRESA = $cod_empresa) = 0 
										AND IP.COD_EMPRESA = $cod_empresa";

					$arraySelSub = mysqli_query(connTemp($cod_empresa,""),trim($sqlImportSub));

					while($qrSelSub = mysqli_fetch_assoc($arraySelSub)){
						if(isset($qrSelSub) && $qrSelSub['DES_SUBCATE'] != ''){

							$cod_externo = $qrSelSub['COD_SUBEXTE'];
							$des_subcate = substr($qrSelSub['DES_SUBCATE'],0,29);

							if($qrSelSub['DES_CATEGOR'] != ''){
								$cod_categor = "(SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR = '".substr($qrSelSub['DES_CATEGOR'],0,19)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
							}else{
								$cod_categor = 0;
							}

							// fnEscreve($qrSelSub['DES_CATEGOR']);
							// fnEscreve($cod_categor);

							$sqlInsertSub .= "INSERT INTO SUBCATEGORIA(
															DES_SUBCATE,
															COD_SUBEXTE,
															COD_CATEGOR,
															COD_EMPRESA
														) VALUES(
															'$des_subcate',
															'$cod_externo',
															$cod_categor,
															 $cod_empresa
														); ";
							// fnEscreve($sqlInsertSub);
							// exit();
						}
					}

					// fnEscreve($sqlInsertSub);

					// exit();

					mysqli_multi_query(connTemp($cod_empresa,""),trim($sqlInsertSub));
					sleep(1);

					$sqlImportForn = "SELECT DISTINCT IP.NOM_FORNECEDOR, IP.COD_EXTFORN FROM IMPORT_PRODUTOS IP 
										WHERE (SELECT COUNT(NOM_FORNECEDOR) FROM FORNECEDORMRKA WHERE NOM_FORNECEDOR = IP.NOM_FORNECEDOR AND COD_EMPRESA = $cod_empresa) = 0 
										AND IP.COD_EMPRESA = $cod_empresa";

					$arraySelForn = mysqli_query(connTemp($cod_empresa,""),trim($sqlImportForn));

					while($qrSelForn = mysqli_fetch_assoc($arraySelForn)){
						if(isset($qrSelForn) && $qrSelForn['NOM_FORNECEDOR'] != ''){

							$cod_externo = $qrSelForn['COD_EXTFORN'];
							$nom_fornecedor = substr($qrSelForn['NOM_FORNECEDOR'],0,29);

							$sqlInsertForn .= "INSERT INTO FORNECEDORMRKA(
															NOM_FORNECEDOR,
															COD_EXTERNO,
															COD_EMPRESA
														) VALUES(
															'$nom_fornecedor',
															'$cod_externo',
															 $cod_empresa
														); ";
						}
					}

					// $sqlUpdateAll = "UPDATE IMPORT_PRODUTOS IP SET
					// 						IP.COD_CATEGOR = (SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR = IP.DES_CATEGOR AND COD_EMPRESA = $cod_empresa),
					// 						IP.COD_SUBCATE = (SELECT COD_SUBCATE FROM SUBCATEGORIA WHERE DES_SUBCATE = IP.DES_SUBCATE AND COD_EMPRESA = $cod_empresa),
					// 						IP.NOM_FORNECEDOR = (SELECT COD_FORNECEDOR FROM FORNECEDORMRKA WHERE NOM_FORNECEDOR = IP.NOM_FORNECEDOR AND COD_EMPRESA = $cod_empresa)
					// 					WHERE 
					// 					IP.COD_EMPRESA = $cod_empresa; ";


					mysqli_multi_query(connTemp($cod_empresa,""),trim($sqlInsertForn));
					sleep(1);

					$sqlUpdt = "UPDATE IMPORT_PRODUTOS IP
								LEFT JOIN CATEGORIA CT ON CT.COD_EXTERNO = IP.COD_EXTCAT AND CT.COD_EMPRESA = $cod_empresa AND CT.COD_EXTERNO != 0
								LEFT JOIN SUBCATEGORIA SC ON SC.COD_SUBEXTE = IP.COD_SUBEXTE AND SC.COD_EMPRESA = $cod_empresa AND SC.COD_SUBEXTE != 0
								LEFT JOIN FORNECEDORMRKA FMK ON FMK.COD_EXTERNO = IP.COD_EXTFORN AND FMK.COD_EMPRESA = $cod_empresa AND FMK.COD_EXTERNO != 0
								SET 
									IP.COD_CATEGOR = CT.COD_CATEGOR,
									IP.COD_SUBCATE = SC.COD_SUBCATE,
									IP.COD_FORNECEDOR = FMK.COD_FORNECEDOR
								WHERE IP.COD_EMPRESA = $cod_empresa;

								SET 
									IP.COD_CATEGOR = (SELECT COD_CATEGOR FROM categoria 
															WHERE DES_CATEGOR = IP.DES_CATEGOR AND COD_EMPRESA = $cod_empresa
															ORDER BY 1 LIMIT 1),
									IP.COD_SUBCATE = (SELECT COD_SUBCATE FROM subcategoria
															WHERE DES_SUBCATE = IP.DES_SUBCATE AND COD_EMPRESA = $cod_empresa
															ORDER BY 1 LIMIT 1),
									IP.COD_FORNECEDOR = (SELECT COD_FORNECEDOR FROM FORNECEDORMRKA 
															WHERE NOM_FORNECEDOR = IP.NOM_FORNECEDOR AND COD_EMPRESA = $cod_empresa
															ORDER BY 1 LIMIT 1)
								WHERE IP.COD_EMPRESA = $cod_empresa
								";


					mysqli_multi_query(connTemp($cod_empresa,""),trim($sqlUpdt));

					// mysqli_query(connTemp($cod_empresa,""),trim($sqlUpdateAll));
					sleep(2);

				// ------------------------------------------------------------------------------------------------------------------------------------- FIM DA CRIAÇÃO DOS ITENS 

				if($escolha != "ATUALIZAR" && $escolha != ""){

					$sql = "SELECT IP.* FROM IMPORT_PRODUTOS IP
							WHERE (SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IP.COD_EXTERNO AND PC.COD_EMPRESA = IP.COD_EMPRESA) = 1  
						    AND IP.COD_EMPRESA = $cod_empresa";

					////fnEscreve($sql);
					$ultimaCat = "";
					$ultimaSub = "";
					$ultimoForn = "";
					$sql1 = "";

					$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql));
					while($qrProd = mysqli_fetch_assoc($arrayQuery)){

						$cod_externo = $qrProd['COD_EXTERNO'];
						$ean = $qrProd['EAN'];
						if($qrProd['LOG_PBM'] != "") $log_pbm = $qrProd['LOG_PBM']; else $log_pbm = "N";
						$des_produto = $qrProd['DES_PRODUTO'];
						$val_custo = $qrProd['VAL_CUSTO'];
						$val_preco = $qrProd['VAL_PRECO'];
						$des_categor = $qrProd['DES_CATEGOR'];
						$cod_categor = fnLimpaCampoZero($qrProd['COD_CATEGOR']);
						$des_subcate = $qrProd['DES_SUBCATE'];
						$cod_subcate = fnLimpaCampoZero($qrProd['COD_SUBCATE']);
						$nom_fornecedor = $qrProd['NOM_FORNECEDOR'];
						$cod_fornecedor = fnLimpaCampoZero($qrProd['COD_FORNECEDOR']);

						if($cod_categor == 0){
							$cod_categor = "(SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR = '".substr($qrProd['DES_CATEGOR'],0,19)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
						}

						if($cod_subcate == 0){
							$cod_subcate = "(SELECT COD_SUBCATE FROM SUBCATEGORIA WHERE DES_SUBCATE = '".substr($qrProd['DES_SUBCATE'],0,29)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
						}

						if($cod_fornecedor == 0){
							$cod_fornecedor = "(SELECT COD_FORNECEDOR FROM FORNECEDORMRKA WHERE NOM_FORNECEDOR = '".substr($qrProd['NOM_FORNECEDOR'],0,29)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
						}


						$sqlCodProd = "SELECT COD_PRODUTO FROM PRODUTOCLIENTE WHERE COD_EXTERNO = $cod_externo AND COD_EMPRESA = $cod_empresa";
						$arrayCodProd = mysqli_query(connTemp($cod_empresa,""),trim($sqlCodProd));
						$qrCodProd = mysqli_fetch_assoc($arrayCodProd);
						$cod_produto = $qrCodProd['COD_PRODUTO'];

						$sql1 .= "UPDATE PRODUTOCLIENTE SET
								COD_EXTERNO = '$cod_externo',
								EAN = '$ean',
								DES_PRODUTO = '$des_produto',
								COD_CATEGOR = $cod_categor,
								COD_SUBCATE = $cod_subcate,
								COD_FORNECEDOR = $cod_fornecedor,
								COD_ALTERAC = $cod_usucada,
								DAT_ALTERAC = NOW(),
								LOG_PRODPBM = '$log_pbm',
								VAL_CUSTO = '$val_custo',
								VAL_PRECO = '$val_preco',
								LOG_IMPORT='S'
								WHERE COD_PRODUTO = $cod_produto
								AND COD_EMPRESA = $cod_empresa;
								";

						$altera = 1;

					}

					if($sql1 != ""){

						// $insert = rtrim($insert,',');

						$arrayUpdate = explode(';', $sql1);
						$qtd_total = count($arrayUpdate);
						$stringInsert = "";
						$countRef = 99;

						for ($i=0; $i <= $qtd_total ; $i++) { 

							$stringUpdate .= $arrayUpdate[$i].";";

							if($i == $countRef){

								mysqli_multi_query(connTemp($cod_empresa,""),trim($stringUpdate));
								// exit();

								$countRef += 100;
								$stringUpdate = "";

							}

						}

						if($stringUpdate != ""){

							mysqli_multi_query(connTemp($cod_empresa,""),trim($stringUpdate));

						}

						echo($duplicado);
						// sleep(5);
					}

					// if($sql1 != ""){
					// 	mysqli_multi_query(connTemp($cod_empresa,""),trim($sql1));
					sleep(6);
					// 	//fnEscreve($sql1);
					// }

				}


					$sqlProd = "
								  	SELECT  IP.*,
									(SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IP.COD_EXTERNO AND 
																				   PC.COD_EMPRESA = IP.COD_EMPRESA)
									AS TEMPRODUTO
									FROM IMPORT_PRODUTOS IP
									LEFT JOIN PRODUTOCLIENTE PC on PC.COD_EXTERNO = IP.COD_EXTERNO AND 
																    PC.COD_EMPRESA = IP.COD_EMPRESA
									WHERE IP.COD_EMPRESA = $cod_empresa
								";


					$arrayQueryProd = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));

					$insertProd = "";

					while($qrProd = mysqli_fetch_assoc($arrayQueryProd)){

						$cod_externo = $qrProd['COD_EXTERNO'];
						$ean = $qrProd['EAN'];
						if($qrProd['LOG_PBM'] != "") $log_pbm = $qrProd['LOG_PBM']; else $log_pbm = "N";
						$des_produto = $qrProd['DES_PRODUTO'];
						$val_custo = $qrProd['VAL_CUSTO'];
						$val_preco = $qrProd['VAL_PRECO'];
						$des_categor = $qrProd['DES_CATEGOR'];
						$cod_categor = fnLimpaCampoZero($qrProd['COD_CATEGOR']);
						$des_subcate = $qrProd['DES_SUBCATE'];
						$cod_subcate = fnLimpaCampoZero($qrProd['COD_SUBCATE']);
						$nom_fornecedor = $qrProd['NOM_FORNECEDOR'];
						$cod_fornecedor = fnLimpaCampoZero($qrProd['COD_FORNECEDOR']);

						if($cod_categor == 0){
							$cod_categor = "(SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR = '".substr($qrProd['DES_CATEGOR'],0,19)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
						}

						if($cod_subcate == 0){
							$cod_subcate = "(SELECT COD_SUBCATE FROM SUBCATEGORIA WHERE DES_SUBCATE = '".substr($qrProd['DES_SUBCATE'],0,29)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
						}

						if($cod_fornecedor == 0){
							$cod_fornecedor = "(SELECT COD_FORNECEDOR FROM FORNECEDORMRKA WHERE NOM_FORNECEDOR = '".substr($qrProd['NOM_FORNECEDOR'],0,29)."' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1)";
						}

						if($qrProd['TEMPRODUTO'] == 0){

							$insertProd .= "(
									'$cod_externo',
									$cod_empresa,
									'$ean',
									'$des_produto',
									$cod_categor,
									$cod_subcate,
									$cod_fornecedor,
									$cod_usucada,
									'$log_pbm',
									$val_custo,
									$val_preco,
									'S',
									NOW()
									);";				
						
						}else{
							// incrementando contador caso produto exista na lista de produtos e na blacklist simultaneamente
							$jaexiste++;
						}

					}

				if($insertProd != ""){

					// $insert = rtrim($insert,',');

					$arrayInsert = explode(';', $insertProd);
					$qtd_total = count($arrayInsert);
					$stringInsert = "";
					$countRef = 99;

					for ($i=0; $i <= $qtd_total ; $i++) { 

						$stringInsert .= $arrayInsert[$i].",";

						if($i == $countRef){

							$stringInsert = rtrim(trim($stringInsert),',');

							$sql = "INSERT INTO PRODUTOCLIENTE(
									COD_EXTERNO,
									COD_EMPRESA,
									EAN,
									DES_PRODUTO,
									COD_CATEGOR,
									COD_SUBCATE,
									COD_FORNECEDOR,
									COD_USUCADA,
									LOG_PRODPBM,
									VAL_CUSTO,
									VAL_PRECO,
									LOG_IMPORT,
									DAT_CADASTR
									) VALUES $stringInsert";
							fnEscreve($sql);
							exit();
							mysqli_multi_query(connTemp($cod_empresa,""),trim($sql));
							// exit();

							$sql1 = "";

							$countRef += 100;
							$stringInsert = "";

						}

						# code...
					}

					if($stringInsert != ""){

						$sql = "INSERT INTO PRODUTOCLIENTE(
											COD_EXTERNO,
											COD_EMPRESA,
											EAN,
											DES_PRODUTO,
											COD_CATEGOR,
											COD_SUBCATE,
											COD_FORNECEDOR,
											COD_USUCADA,
											LOG_PRODPBM,
											VAL_CUSTO,
											VAL_PRECO,
											LOG_IMPORT,
											DAT_CADASTR
											) VALUES $stringInsert";
						fnEscreve($sql);
						exit();
						mysqli_multi_query(connTemp($cod_empresa,""),trim($sql));

					}

					echo($duplicado);
					// sleep(5);
				}
				

				?>

				<div class="push100"></div>

				<div class="row">

					<div class="col-md-4"></div>

					<?php 

					$sqlLinhas = "SELECT COUNT(*) AS LINHAS FROM IMPORT_PRODUTOS WHERE COD_EMPRESA = $cod_empresa";
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
								$qrLinhas = mysqli_fetch_assoc($result);

					// comparando nro de linhas da planilha com nro de produtos existentes na lista e na blacklist

					if($jaexiste != $qrLinhas['LINHAS']){
					?>

						<div class="col-md-4 text-center">
							<h4>Lista de produtos importada com <b>sucesso</b>!</h4>
						</div>

					<?php }else if($jaexiste == $qrLinhas['LINHAS'] && $altera == 0){ ?>

						<div class="col-md-4 text-center">
							<h4>Lista de produtos já existe. <b>Nenhum dado</b> foi alterado.</h4>
						</div>

					<?php }else{ ?>

						<div class="col-md-4 text-center">
							<h4>Lista de produtos atualizada com <b>sucesso</b>!</h4>
						</div>

					<?php } ?>

				</div>

				<div class="push100"></div>

				<hr>

				<div class="col-md-10"></div>

				<div class="col-md-2">
					<a href="action.do?mod=<?php echo fnEncode(1321)."&id=".fnEncode($cod_empresa); ?>" class="col-md-12 btn btn-success concluir">Concluir</a>
				</div>
					

				<div class="push10"></div>

				<script>

				</script>

			<?php

			}

			 

		break;
		
	}	
?>
