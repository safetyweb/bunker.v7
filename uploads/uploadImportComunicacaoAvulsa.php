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

		$sql = "DELETE FROM IMPORT_COMUNICAAV WHERE COD_EMPRESA = $cod_empresa AND COD_LISTA = 0";
				mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());

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
				$ultimo_cli = 0;
				$insert = "";

				/*
				Glossário do array da planilha:

				$row[0] = CLIENTE;
				$row[1] = DDD OU CELULAR - CORINGA;
				$row[2] = CELULAR SE CORINGA DDD OU EMAIL SE CORINGA CELULAR;
				$row[3] = EMAIL SE CORINGA DDD;
				*/

				
				foreach ($reader->getSheetIterator() as $sheet) {

					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					
					foreach ($sheet->getRowIterator() as $row) {

						if($contador == 0){

							$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));


						}else if(count($colunas) <= 4 && count($colunas) > 2){

							$nom_cliente = fnLimpaCampo(trim($row[0]));
							$coringa = fnLimpaCampo(trim($row[1]));

							if(strlen($coringa) == 2 && $coringa != ""){

								$num_celular = fnLimpaDoc($coringa.fnLimpaCampo(trim($row[2])));
								$des_emailus = fnLimpaCampo(trim($row[3]));

							}else if($coringa == ""){

								$num_celular = fnLimpaDoc(fnLimpaCampo(trim($row[2])));
								$des_emailus = fnLimpaCampo(trim($row[3]));

							}else{

								$num_celular = fnLimpaDoc($coringa);
								$des_emailus = fnLimpaCampo(trim($row[2]));

							}

							//buscando string sql pelo código externo do produto
							if (strpos($insert, $num_celular)){

								//incrementando o contador caso o cliente seja duplicado (para informar o nro de registros duplicados)
							    $duplicado++;

							}else{

								//comparando o ultimo cod externo com o cod externo a ser gravado
								if($num_celular != ""){

									$insert .= "(
													$cod_empresa,
													'$nom_cliente',
													'$num_celular',
													'$des_emailus',
													$cod_usucada
												),";

								}else{
									//incrementando o contador caso o cod externo seja duplicado (para informar o nro de registros duplicados)
									if($num_celular){

										$ultimo_cli = $num_celular;
										$duplicado++;

									}

								}

							}

						}else{

							echo 'A planilha deve conter as colunas: "Cliente", "DDD (opcional)", "Celular" e "EMAIL". Revise sua planilha e tente novamente.';
							exit();

						}

						$contador++;

					}

				}
				//fnEscreve($sql1);
				if($insert != ""){

					$insert = rtrim($insert,',');

					$sql1 = "INSERT INTO IMPORT_COMUNICAAV(
										COD_EMPRESA,
										NOM_CLIENTE,
										NUM_CELULAR,
										DES_EMAILUS,
										COD_USUCADA
										) VALUES $insert";
					//fnEscreve($sql1);
					mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die("É possível que a ordem das colunas da planilha esteja incorreta.");

					unset($sql1);

					echo($duplicado);
					// sleep(2);

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

				<div class="col-md-12">

					<div class="col-md-7">
						<div class="form-group">
							<label for="inputName" class="control-label">Nome e Tipo do Arquivo</label>
							<input type="text" class="form-control input-sm leitura2" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" readonly>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="inputName" class="control-label">Linhas</label>
							<?php
								$sqlLinhas = "SELECT COUNT(COD_REGISTRO) AS LINHAS FROM IMPORT_COMUNICAAV WHERE COD_EMPRESA = $cod_empresa AND COD_LISTA = 0";
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas)) or die(mysqli_error());
								$qrLinhas = mysqli_fetch_assoc($result);
							?>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?=$qrLinhas[LINHAS];?>" readonly>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="inputName" class="control-label">Duplicados</label>
							<input type="text" class="form-control input-sm leitura2" name="QTD_DUPLICADOS" id="QTD_DUPLICADOS" maxlength="45" value="" readonly>
						</div>
					</div>

				</div>

			</div>

			<div class="row">
				
				<div class="col-md-12">
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
			    				<th>Cliente</th>
			    				<th>Celular</th>
			    				<th>Email</th>
			    			</tr>
			    		</thead>

			    		<tbody id="relConteudo">
					
							<?php

							$sqlProd = "SELECT * FROM IMPORT_COMUNICAAV WHERE COD_EMPRESA = $cod_empresa AND COD_LISTA = 0
										ORDER BY NOM_CLIENTE
									    LIMIT 20
									  ";

							$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd)) or die(mysqli_error());
							////fnEscreve($qrLinhas['LINHAS']);

							while($qrProd = mysqli_fetch_assoc($result)){

							?>
								<tr>
									<td><?=$qrProd['NOM_CLIENTE']?></td>
									<td class="sp_celphones"><?=fnCorrigeTelefone($qrProd['NUM_CELULAR'])?></td>
									<td><?=$qrProd['DES_EMAILUS']?></td>
								</tr>
							<?php
							}
							?>

						</tbody>

					</table>

					<?php
						if($qrLinhas['LINHAS'] > 20){ ?>
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
				<button type="submit" class="col-md-12 btn btn-primary next2">Importar<i class="fas fa-arrow-right pull-right"></i></button>
			</div>
				

			<div class="push10"></div>

			<script>

				var cont = 0;
				$('#loadMore').click(function(){
					
					cont +=20;

					if(cont >= "<?php echo $qrLinhas['LINHAS']; ?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
					}

					$.ajax({
						type: "GET",
						url: "../uploads/uploadImportComunicacaoAvulsa.php?acao=loadMore&itens="+cont+"&id=<?php echo $cod_empresa; ?>",
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
					$.ajax({
						type: "GET",
						url: "../uploads/uploadImportComunicacaoAvulsa.do?acao=importar&id=<?php echo $cod_empresa; ?>",
						beforeSend:function(){
							$('#passo2').hide();
							$('#passo3').show();
							$("#passo3").html('<div class="loading" style="width: 100%;"></div>');
						},
						success:function(data){
							$("#passo3").html(data);
							$("#step3 div.fundo, #step3 a.btn").addClass('fundoAtivo');
						},
						error:function(){
							alert('Erro ao carregar...');
						}
					});
				});

				var SPMaskBehavior = function (val) {
				  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
				  onKeyPress: function(val, e, field, options) {
					  field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};			
				
				$('.sp_celphones').mask(SPMaskBehavior, spOptions);
				
			</script>

		<?php 

		break;

		case "importar": //Rotina de confirmação dos dados enviados

			$cod_empresa = fnLimpaCampoZero($_GET['id']);
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$sql = "INSERT INTO COMUNICAAV_PARAMETROS(
									COD_EMPRESA,
									QTD_LISTA,
									COD_USUCADA
								) VALUES(
									$cod_empresa,
									(SELECT COUNT(0) FROM IMPORT_COMUNICAAV 
									 WHERE COD_EMPRESA = $cod_empresa 
									 AND COD_LISTA = 0),
									 $cod_usucada
								)";

			mysqli_query(connTemp($cod_empresa,""),trim($sql));

			$sqlUp = "UPDATE IMPORT_COMUNICAAV 
					  SET COD_LISTA = (SELECT MAX(COD_LISTA) FROM COMUNICAAV_PARAMETROS 
										 WHERE COD_EMPRESA = $cod_empresa 
										 AND COD_USUCADA = $cod_usucada)
					  WHERE COD_EMPRESA = $cod_empresa AND COD_LISTA = 0";

			mysqli_query(connTemp($cod_empresa,""),trim($sqlUp));



		?>

			<div class="push100"></div>

			<div class="row">

				<div class="col-md-4 col-md-offset-4 text-center">
					<h4>Importação realizada com <b>sucesso</b>!</h4>
				</div>

			</div>

				

			<div class="push100"></div>

			<script>
				$("#IMPORTADO").val("S");
				$('#formulario').validator('validate');			
				$("#formulario #hHabilitado").val('S');
			</script>

		<?php

		break;

		case "loadMore":

		?>
		
		<?php

			$limite = $_GET['itens'];

			////fnEscreve($limite);

			$sqlProd = "SELECT * FROM IMPORT_COMUNICAAV WHERE COD_EMPRESA = $cod_empresa AND COD_LISTA = 0
						ORDER BY NOM_CLIENTE
					    LIMIT $limite,20
					 ";

			$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd)) or die(mysqli_error());

			while($qrProd = mysqli_fetch_assoc($result)){

		?>
				<tr>
					<td><?=$qrProd['NOM_CLIENTE']?></td>
					<td class="sp_celphones"><?=fnCorrigeTelefone($qrProd['NUM_CELULAR'])?></td>
					<td><?=$qrProd['DES_EMAILUS']?></td>
				</tr>
		<?php
			}
		?>
			

<?php 

		break;

		
	}	
?>
