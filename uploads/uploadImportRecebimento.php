<?php

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	//echo fnDebug('true');
	////fnEscreve('Entra no ajax');

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	$cod_empresa = fnlimpacampozero(fndecode($_GET["id"]));
	if(isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	
	////fnEscreve($cod_empresa);

	switch($acao){

		case "gravar": //Rotina de gravação da planilha na tabela 'temporária'

		$cod_contrat =fnlimpacampozero(fndecode($_GET["idCT"]));
		$cod_conveni = fnlimpacampozero(fndecode($_GET["idC"]));

		//Excluí todos os registros da tabela 'temporária'
		$sql = "DELETE FROM IMPORT_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CONVENI = $cod_conveni 
				AND COD_CONTRAT = $cod_contrat";	
				mysqli_query(connTemp($cod_empresa,""),trim($sql));
		//Verifica se o arquivo é vázio, se não, entra na rotina
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
				
				foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					
					foreach ($sheet->getRowIterator() as $row) {
						foreach($row as $k => $r){
							if (is_object($r)){
								$row[$k] = $r->format('d/m/Y');
							}else{
								$row[$k] = $r;
							}
						}
						if($contador == 0){
							$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));
							//fnEscreve(count($colunas));
							//fnEscreve(count($colunas));
						}
						else if($contador != 0 && count($colunas) == 5){
							//Guarda os campos da Planilha e formata os campos necessários
							$des_nomebem = $row[0];
							$cod_externo = $row[1];
							$val_medicao = fnValorSql(fnvalor($row[2],2));	
							$val_evolucao = $row[3];
							$val_total = fnValorSql(fnvalor($row[4],2));
							$log_import = 'S';

							if(trim($row[0]) != ""){
							$insert .= "(
									$cod_externo,
									$cod_empresa,
									$cod_conveni,
									$cod_contrat,
									'$des_nomebem',
									$val_evolucao,
									'$val_medicao',
									'$val_total',
									'$log_import'
								);";
							}
						}else{
							echo 'A planilha deve conter exatamente 7 colunas. Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
				}
				
				//fnEscreve($sql1);
				// exit();

				//Rotina de inserção de registros na tabela 'temporária'
				if($insert != ""){

					// $insert = rtrim($insert,',');

					$arrayInsert = explode(';', $insert);
					$arrayInsert = array_filter($arrayInsert);
					$qtd_total = count($arrayInsert);
					$stringInsert = "";
					$countRef = 99;

					for ($i=0; $i <= $qtd_total ; $i++) { 
						if ($countRef > $qtd_total){
							$countRef = $qtd_total;
						}
						$stringInsert .= $arrayInsert[$i].",";

						if($i == $countRef){

							$stringInsert = rtrim(trim($stringInsert),',');

							$sql1 = "INSERT INTO IMPORT_RECEBIMENTO(
												COD_EXTERNO,
												COD_EMPRESA,
												COD_CONVENI,
												COD_CONTRAT,
												DES_NOMEBEM,
												VAL_EVOLUCAO,
												VAL_MEDICAO,
												VAL_TOTAL,
												LOG_IMPORT
												) VALUES $stringInsert";
							 //print_r($arrayInsert) ."<br><br>";
							mysqli_query(connTemp($cod_empresa,""),trim($sql1));

							$sql1 = "";

							$countRef += 100;
							$stringInsert = "";

						}

					}

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
								$sqlLinhas = "SELECT COUNT(COD_IMPORT) AS LINHAS FROM IMPORT_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa";
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
								$qrLinhas = mysqli_fetch_assoc($result);
							?>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?=$qrLinhas['LINHAS'];?>" readonly>
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
			    				<th>Cod. Externo</th>
			    				<th>Descrição do Produto</th>
								<th>Quantidade</th>
								<th>Valor Unitário</th>
								<th class="text-right">Total</th>
			    			</tr>
			    		</thead>

			    		<tbody id="relConteudo">
					
							<?php

							$sqlProd ="SELECT * FROM IMPORT_RECEBIMENTO";

							$result = mysqli_query(connTemp($cod_empresa,""),$sqlProd);

							while($qrProd = mysqli_fetch_assoc($result)){

								?>
								<tr>
									<td><?= $qrProd['COD_EXTERNO']; ?></td>
									<td><?= $qrProd['DES_NOMEBEM']; ?></td>
									<td><?= fnvalor($qrProd['VAL_MEDICAO'],2); ?></td>
									<td><?= fnValor($qrProd['VAL_EVOLUCAO'],2); ?></td>
									<td class="text-right"><?= fnValor($qrProd['VAL_TOTAL'],2); ?></td>
								</tr>
								<?php
							}

							echo $cod_contrat;
								?>

						</tbody>

					</table>

					<?php
						$sql= "SELECT COUNT(COD_IMPORT) AS LINHAS FROM IMPORT_RECEBIMENTO";
						$arraylinhas = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));
						$qrLinhas = mysqli_fetch_assoc($arraylinhas);
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
					url: "../uploads/uploadImportRecebimento.php?acao=confirmar&id=<?=fnEncode($cod_empresa); ?>",
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

					if(cont >= "<?= $qrLinhas['LINHAS']; ?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
					}

					$.ajax({
						type: "GET",
						url: "../uploads/uploadImportRecebimento.php?acao=loadMore&itens="+cont+"&id=<?= fnEncode($cod_empresa); ?>",
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
				$cod_empresa = fnDecode($_GET['id']);
		?>

			<div class="push50"></div>

			<div class="row">
				<div class="col-xs-3 col-xs-offset-3">
					<div class="form-group">
						<label for="inputName" class="control-label required">Nº do Recebimento</label>
						<input type="text" class="form-control input-sm" name="NUM_MEDICAO" id="NUM_MEDICAO" value="<?= $num_recebim ?>" required>
					</div>
					<div class="help-block with-errors"></div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label required">Data do Recebimento</label>
						<div class="input-group date datePicker" id="DAT_INI_GRP">
							<input type='text' class="form-control input-sm data" name="DAT_MEDICAO" id="DAT_MEDICAO" value="<?= $dat_medicao ?>" required />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
						<div class="help-block with-errors"></div>
					</div>
				</div>
			</div>

            <div class="push20"></div>

			<div class="row">

				<div class="col-md-4"></div>

				<div class="col-md-4 text-center">
					<h4><b>O que deseja fazer?</b></h4>
				</div>

			</div>

			<div class="row text-center">
				
				<div class="col-md-4"></div>

				<div class="col-md-4">
					<input type="radio" id="ATUALIZAR" name="RADIO" checked value="ATUALIZAR">
					<label for="ATUALIZAR">Inserir somente novos produtos na lista de produtos</label>
				</div>

				<!--<div class="col-md-2">
					<input type="radio" id="SUBSTITUIR" name="RADIO" value="SUBSTITUIR">
					<label for="SUBSTITUIR">Inserir produtos não existentes e atualizar produtos já existentes de mesmo nome na lista de produtos</label>
				</div>-->

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
        <script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
        <script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
        <script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
		<script>
                    
            $(document).ready(function() {

                $('.datePicker').datetimepicker({
                    format: 'DD/MM/YYYY',
                }).on('changeDate', function(e) {
                    $(this).datetimepicker('hide');
                });
            });

			$('.next3').click(function(){
				$.ajax({
					type: "POST",
					url: "../uploads/uploadImportRecebimento.php?id=<?=fnencode($cod_empresa) ?>",
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

		$cod_empresa = fnDecode($_GET['id']);

		?>
		
		<?php

		$limite = $_GET['itens'];

				////fnEscreve($limite);

				$sqlProd = "SELECT COUNT(COD_IMPORT) AS LINHAS FROM IMPORT_RECEBIMENTO
							ORDER BY COD_EXTERNO
						    LIMIT $limite,20
						 ";

				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));

				echo $sqlProd;

				while($qrProd = mysqli_fetch_assoc($result)){

				?>
				<tr>
					<td><?= $qrProd['COD_EXTERNO']; ?></td>
					<td><?= $qrProd['DES_NOMEBEM']; ?></td>
					<td><?= fnvalor($qrProd['VAL_MEDICAO'],2); ?></td>
					<td><?= fnValor($qrProd['VAL_EVOLUCAO'],2); ?></td>
					<td class="text-right"><?= fnValor($qrProd['VAL_TOTAL'],2); ?></td>
				</tr>
				<?php
				}
				?>
			

<?php 

		break;

		default:

		$cod_empresa = fnDecode($_GET['id']);
		$dat_medicao = fndatesql($_POST['DAT_MEDICAO']);
		$num_recebim = fnlimpacampozero($_POST['NUM_MEDICAO']);

		//rotinas de iserção e substituição de produtos da importação

			if(isset($_POST['RADIO'])){

				$escolha = $_POST['RADIO'];

					$jaexiste=0;
					$altera=0;
					$sqlInsertCat = "";
					$sqlInsertSub = "";
					$sqlInsertForn = "";

					$sqlUpdt = "UPDATE CONTROLE_RECEBIMENTO CR
								INNER JOIN IMPORT_RECEBIMENTO IP ON IP.COD_CONTRAT = CR.COD_CONTRAT AND IP.COD_CONVENI = CR.COD_CONVENI
								SET 
									CR.DES_NOMEBEM = IP.DES_NOMEBEM,
									CR.NUM_MEDICAO = IP.NUM_MEDICAO,
									CR.DAT_MEDICAO = IP.DAT_MEDICAO,
									CR.VAL_EVOLUCAO = IP.VAL_EVOLUCAO,
									CR.VAL_MEDICAO = IP.VAL_MEDICAO,
									CR.VAL_TOTAL = IP.VAL_TOTAL
								WHERE CR.COD_EMPRESA = $cod_empresa
								AND CR.COD_CONTRAT = $cod_contrat
								AND CR.COD_CONVENI = $cod_conveni
								";


					// fnEscreve($sqlUpdt);
					mysqli_query(connTemp($cod_empresa,""),trim($sqlUpdt));


					// mysqli_query(connTemp($cod_empresa,""),trim($sqlUpdateAll));
					sleep(2);

				// ------------------------------------------------------------------------------------------------------------------------------------- FIM DA CRIAÇÃO DOS ITENS 

				if($escolha != "ATUALIZAR" && $escolha != ""){

					$altera = 1;

					$sqlUpdtProd = "UPDATE CONTROLE_RECEBIMENTO CR
									INNER JOIN IMPORT_RECEBIMENTO IP ON IP.COD_CONTRAT = CR.COD_CONTRAT AND IP.COD_CONVENI = CR.COD_CONVENI
									SET
									CR.DES_NOMEBEM = IP.DES_NOMEBEM,
									CR.NUM_MEDICAO = IP.NUM_MEDICAO,
									CR.DAT_MEDICAO = IP.DAT_MEDICAO,
									CR.VAL_EVOLUCAO = IP.VAL_EVOLUCAO,
									CR.VAL_MEDICAO = IP.VAL_MEDICAO,
									CR.VAL_TOTAL = IP.VAL_TOTAL,
									CR.LOG_IMPORT = 'S'
									AND CR.COD_CONTRAT = $cod_contrat
									AND CR.COD_CONVENI = $cod_conveni";

					// fnEscreve($sqlUpdtProd);

				}else{


				mysqli_query(connTemp($cod_empresa,""),trim($sqlUpdtProd));

				sleep(2);

				$sqlInsProd = "INSERT INTO CONTROLE_RECEBIMENTO (
											COD_MEDICAO,
											COD_EMPRESA,
											COD_CONVENI,
											COD_CONTRAT,
											DES_NOMEBEM,
											NUM_MEDICAO,
											DAT_MEDICAO,
											VAL_EVOLUCAO,
											VAL_MEDICAO,
											VAL_TOTAL,
											LOG_IMPORT,
											COD_USUCADA,
											TIP_CONTROLE
											)
							   SELECT 		
											COD_EXTERNO,
											COD_EMPRESA,
											COD_CONVENI,
											COD_CONTRAT,
											DES_NOMEBEM,
											$num_recebim,
											$dat_medicao,
											VAL_EVOLUCAO,
											VAL_MEDICAO,
											VAL_TOTAL,
											LOG_IMPORT,
											$cod_usucada,
											'RCB'
							   FROM IMPORT_RECEBIMENTO IP 							  
							   WHERE IP.COD_EMPRESA = $cod_empresa";

				//echo($sqlInsProd);
								
				mysqli_query(connTemp($cod_empresa,""),trim($sqlInsProd));
				}
				

				?>

				<div class="push100"></div>

				<div class="row">

					<div class="col-md-4"></div>

					<?php 

					$sqlLinhas = "SELECT COUNT(*) AS LINHAS FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND LOG_IMPORT = 'S'";
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
								$qrLinhas = mysqli_fetch_assoc($result);

					// comparando nro de linhas da planilha com nro de produtos existentes na lista e na blacklist

					if($qrLinhas['LINHAS'] > 0){
					?>

						<div class="col-md-4 text-center">
							<h4>Lista de produtos importada com <b>sucesso</b>!</h4>
						</div>
						<script>
							parent.$("#LOG_IMPORTOU").val("S");
						</script>

					<?php }else if($qrLinhas['LINHAS'] == 0 && $altera == 0){ ?>

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
					<a type="button" style="border-radius:5px;" class="btn btn-info" href="action.php?mod=<?= fnEncode(1807) ?>&id=<?= fnEncode($cod_empresa) ?>&idCT=<?=fnEncode($cod_contrat)?>&idC=<?=fnEncode($cod_conveni)?>&pop=true"><i class="fal fa-check" aria-hidden="true">&nbsp;Concluir</i></a>
				</div>

				<div class="push10"></div>

			<?php

			}		 

		break;
		
	}	
?>
