<?php

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	//echo fnDebug('true');
	////fnEscreve('Entra no ajax');

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	$cod_empresa = fnLimpaCampoZero($_GET['id']);
	$cod_persona = fnLimpaCampoZero($_GET['idx']);
	if(isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	
	$NOME_PERSONA = "";
	if ($cod_persona > 0){
		$sql = "SELECT DES_PERSONA FROM PERSONA
					WHERE COD_EMPRESA = $cod_empresa AND COD_PERSONA=$cod_persona";
		$rs = mysqli_query(connTemp($cod_empresa,""),trim($sql));
		$qrLinhas = mysqli_fetch_assoc($rs);
		$NOME_PERSONA = $qrLinhas['DES_PERSONA'];
	}
	////fnEscreve($cod_empresa);

	switch(@$acao){

		case "gravar": //Rotina de gravação da planilha na tabela 'temporária'

		$sql = "DELETE FROM IMPORT_CLIENTES WHERE COD_EMPRESA = $cod_empresa";
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
				//$reader->setShouldFormatDates(true);

				$reader->open($file_tmp);
				
				$ultimo_cod = 0;
				$insert = "";
				$qtd_insert = 0;
				$inseriu = false;

				/*
				Glossário do array da planilha:

				$row[0] =  CODIGO INTERNO
				$row[1] =  NOME
				$row[2] =  GENERO
				$row[3] =  DT_NASC
				$row[4] =  EMAIL
				$row[5] =  CPFCNPJ
				$row[6] =  TEL_1
				$row[7] =  TEL_2
				$row[8] =  CADASTRO
				$row[9] = ENDERECO
				$row[10] = NUMERO
				$row[11] = COMPLEMENTO
				$row[12] = BAIRRO
				$row[13] = CEP
				$row[14] = CIDADE
				$row[15] = ESTADO
				$row[16] = LOJA_CADASTRO
				$row[17] = ADESAO
				*/

				
				foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					
					foreach ($sheet->getRowIterator() as $row) {
						if($contador == 0){
							$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));
							////fnEscreve(count($colunas));
						}
						else if($contador != 0 && count($colunas) == 18){

							/******AJUSTA CAMPOS FORMATADOS COMO DATA NO EXCEL******************/
							$row = json_decode(json_encode($row), true);
							foreach($row as $k => $v){
								$vlr = (isset($row[$k]["date"])?$row[$k]["date"]:"");
								if ($vlr <> ""){
									list($dt,$hr) = explode(" ",$vlr." ");
									$d = explode("-",$dt."---");
									$row[$k] = $d[2]."/".$d[1]."/".$d[0];
								}
							}
							/********************************************************************/

							// fnEscreve(sprintf("%s",$row[5]));
							// fnEscreve(strlen($row[5]));

							$num_cgcecpf = "";

							if($row[5] != ""){

								if(strlen($row[5]) == 10 || strlen($row[5]) == 14){

									$num_cgcecpf = trim("0$row[5]");

								}else if(strlen($row[5]) == 9 || strlen($row[5]) == 13){

									$num_cgcecpf = trim("00$row[5]");

								}else{

									$num_cgcecpf = trim("$row[5]");

								}

							}
							if (trim(
								 fnLimpaCampo(trim($row[0]))
								.fnLimpaCampo(trim($row[1]))
								.fnLimpaCampo(trim($row[2]))
								.fnLimpaCampo(trim($row[3]))
								.fnLimpaCampo(trim($row[4]))
								.fnLimpaCampo($num_cgcecpf)
								.fnLimpaCampo(trim($row[6]))
								.fnLimpaCampo(trim($row[7]))
								.fnLimpaCampo(trim($row[8]))
								.fnLimpaCampo(trim($row[9]))
								.fnLimpaCampo(trim($row[10]))
								.fnLimpaCampo(trim($row[11]))
								.fnLimpaCampo(trim($row[12]))
								.fnLimpaCampo(trim($row[13]))
								.fnLimpaCampo(trim($row[14]))
								.fnLimpaCampo(trim($row[15]))
								.fnLimpaCampo(trim($row[16]))
								.fnLimpaCampo(trim($row[17]))
							) <> ""){
								$inseriu = true;
								$qtd_insert++;

								$insert .= "(
									'$cod_empresa',
									'".fnLimpaCampo(trim($row[0]))."',
									'".fnLimpaCampo(trim($row[1]))."',
									'".fnLimpaCampo(trim($row[2]))."',
									'".fnLimpaCampo(trim($row[3]))."',
									'".fnLimpaCampo(trim($row[4]))."',
									'".fnLimpaCampo($num_cgcecpf)."',
									'".fnLimpaCampo(trim($row[6]))."',
									'".fnLimpaCampo(trim($row[7]))."',
									'".fnLimpaCampo(trim($row[8]))."',
									'".fnLimpaCampo(trim($row[9]))."',
									'".fnLimpaCampo(trim($row[10]))."',
									'".fnLimpaCampo(trim($row[11]))."',
									'".fnLimpaCampo(trim($row[12]))."',
									'".fnLimpaCampo(trim($row[13]))."',
									'".fnLimpaCampo(trim($row[14]))."',
									'".fnLimpaCampo(trim($row[15]))."',
									'".fnLimpaCampo(trim($row[16]))."',
									'".fnLimpaCampo(trim($row[17]))."',
									'S'
									),";

								//Insere em lotes de 10000
								if ($qtd_insert >= 10000){
									$insert = rtrim($insert,',');

									$sql1 = "INSERT INTO IMPORT_CLIENTES(
														COD_EMPRESA,
														COD_INTERNO,
														NOME,
														GENERO,
														DT_NASC,
														EMAIL,
														CPFCNPJ,
														TEL_1,
														TEL_2,
														CADASTRO,
														ENDERECO,
														NUMERO,
														COMPLEMENTO,
														BAIRRO,
														CEP,
														CIDADE,
														ESTADO,
														LOJA_CADASTRO,
														ADESAO,
														LOG_IMPORT
													) VALUES $insert";
				
									//fnEscreve($sql1);
									// exit();

									mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die("É possível que a ordem das colunas da planilha esteja incorreta.<div style='display:none'>".mysqli_error(connTemp($cod_empresa,""))." $sql1</div>");
									$insert = "";
									$qtd_insert = 0;
								}
								

							}

						}else{
							echo 'A planilha deve conter exatamente 18 colunas: "Código", "Nome", "Gênero", "Data de Nascimento", "Email", "Cpf/Cnpj", "Fone principal", "Fone secundário", "Cadastro", "Endereço", "Número", "Complemento", "Bairro", "CEP", "Cidade", "Estado", "Loja de Cadastro" e "Adesão". Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
				}
				//fnEscreve($sql1);
				if($inseriu){

					if (trim($insert) <> ""){
						//Insere o restante
						$insert = rtrim($insert,',');

						$sql1 = "INSERT INTO IMPORT_CLIENTES(
											COD_EMPRESA,
											COD_INTERNO,
											NOME,
											GENERO,
											DT_NASC,
											EMAIL,
											CPFCNPJ,
											TEL_1,
											TEL_2,
											CADASTRO,
											ENDERECO,
											NUMERO,
											COMPLEMENTO,
											BAIRRO,
											CEP,
											CIDADE,
											ESTADO,
											LOJA_CADASTRO,
											ADESAO,
											LOG_IMPORT
										) VALUES $insert";

						//fnEscreve($sql1);
						// exit();


						mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die("É possível que a ordem das colunas da planilha esteja incorreta.<div style='display:none'>".mysqli_error(connTemp($cod_empresa,""))." $sql1</div>");
					}

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
																SET DT_NASC=INSERT(DT_NASC,7,0,
																						CAST(
																							IF(SUBSTR(DT_NASC,-2) > CAST(DATE_FORMAT(NOW(),'%y') AS UNSIGNED),
																								CAST(DATE_FORMAT(NOW(),'%y') AS UNSIGNED)-1,
																								CAST(DATE_FORMAT(NOW(),'%y') AS UNSIGNED)
																							)
																						AS CHAR))
															WHERE LENGTH(DT_NASC)=8 AND LOG_INVALIDO<>'S' AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
																SET CADASTRO=INSERT(CADASTRO,7,0,
																						CAST(
																							IF(SUBSTR(CADASTRO,-2) > CAST(DATE_FORMAT(NOW(),'%y') AS UNSIGNED),
																								CAST(DATE_FORMAT(NOW(),'%y') AS UNSIGNED)-1,
																								CAST(DATE_FORMAT(NOW(),'%y') AS UNSIGNED)
																							)
																						AS CHAR))
															WHERE LENGTH(CADASTRO)=8 AND LOG_INVALIDO<>'S' AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
																SET GENERO=SUBSTR(UPPER(TRIM(IFNULL(GENERO,''))),1,1)
																WHERE LOG_INVALIDO<>'S' AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
																SET CPFCNPJ=REPLACE(REPLACE(IMPORT_CLIENTES.CPFCNPJ,'.',''),'-','')
																WHERE LOG_INVALIDO<>'S' AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes SET LOG_IMPORT='N', MSG_ERRO='Nome não preenchido!'
																WHERE TRIM(IFNULL(NOME,'')) = ''
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
															SET LOG_IMPORT='N', MSG_ERRO='Loja de cadastro não preenchida!'
																WHERE TRIM(IFNULL(LOJA_CADASTRO,'')) = ''
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes SET LOG_IMPORT='N', MSG_ERRO='Loja de cadastro não existe!', LOG_INVALIDO='S'
																WHERE TRIM(IFNULL(LOJA_CADASTRO,'')) != ''
																AND LOG_INVALIDO<>'S'
																AND (SELECT COUNT(1) FROM WEBTOOLS.UNIDADEVENDA WHERE COD_EXTERNO = LOJA_CADASTRO) = 0
																AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes SET LOG_IMPORT='N', MSG_ERRO='Gênero Inválido!', LOG_INVALIDO='S'
																WHERE SUBSTR(UPPER(TRIM(IFNULL(GENERO,''))),1,1) != 'M' AND SUBSTR(UPPER(TRIM(IFNULL(GENERO,''))),1,1) != 'F'
																 AND SUBSTR(UPPER(TRIM(IFNULL(GENERO,''))),1,1) != '' 
																 AND LOG_INVALIDO<>'S'
																 AND import_clientes.COD_EMPRESA = $cod_empresa");
																
					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
															SET LOG_IMPORT='N', MSG_ERRO='Gênero não preenchido!'
																WHERE TRIM(IFNULL(GENERO,'')) = '' 
																AND IFNULL(COD_CLIENTE,0) <= 0
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes
															SET LOG_IMPORT='N', MSG_ERRO='CPF/CNPJ não preenchido!'
																WHERE TRIM(IFNULL(CPFCNPJ,'')) = ''
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");
					/*
					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes 
															SET LOG_IMPORT='N', MSG_ERRO='Data Nascimento não preenchida!'
																WHERE TRIM(IFNULL(DT_NASC,'')) = ''
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");
					*/
					mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes SET LOG_IMPORT='N', MSG_ERRO='Data Nascimento inválida!', LOG_INVALIDO='S'
																WHERE STR_TO_DATE(import_clientes.DT_NASC, '%d/%m/%Y') IS NULL
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes SET LOG_IMPORT='N', MSG_ERRO='Número do Endereço Inválido!', LOG_INVALIDO='S'
																WHERE (LENGTH(CAST(NUMERO AS UNSIGNED)) != LENGTH(TRIM(NUMERO)))
																AND LOG_INVALIDO<>'S'
																AND (TRIM(NUMERO) != '') AND import_clientes.COD_EMPRESA = $cod_empresa");

					mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes SET LOG_IMPORT='N', MSG_ERRO=CONCAT('CPF/CNPJ \"',CPFCNPJ,'\" Inválido!')
																WHERE TRIM(IFNULL(CPFCNPJ,'')) <> '' AND CPFCNPJ NOT REGEXP '^[0-9]+$'
																AND LOG_INVALIDO<>'S'
																AND import_clientes.COD_EMPRESA = $cod_empresa");

				    //CHECA CPFs DUPLICADOS
					$sql = "SELECT CPFCNPJ,COUNT(0) QTD FROM import_clientes
							WHERE TRIM(IFNULL(CPFCNPJ,'')) <> '' 
							GROUP BY CPFCNPJ
							HAVING COUNT(0) > 1";
					$cpfs = '';
					$result = mysqli_query(connTemp($cod_empresa,""),trim($sql));
					while($linha = mysqli_fetch_assoc($result)){
						$cpfs = $cpfs.($cpfs != ""?",":"")."'".$linha["CPFCNPJ"]."'";
					}
					if ($cpfs != ''){
						mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes SET LOG_IMPORT='N', MSG_ERRO=CONCAT('CPF/CNPJ \"',CPFCNPJ,'\" duplicado no arquivo!')
																	WHERE CPFCNPJ IN ($cpfs)
																	AND LOG_INVALIDO<>'S'
																	AND import_clientes.COD_EMPRESA = $cod_empresa");
					}

					//AJUSTES DIVERSOS
					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes SET NOME = SUBSTR(TRIM(NOME),1,250) WHERE import_clientes.COD_EMPRESA = $cod_empresa");

					//VINCULAR NA TABELA CLIENTES, POR CPF/CNPJ
					mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes
															INNER JOIN clientes ON
																clientes.NUM_CGCECPF=CAST(import_clientes.CPFCNPJ AS UNSIGNED)
																AND CAST(import_clientes.CPFCNPJ AS UNSIGNED) > 0
																AND clientes.cod_empresa = import_clientes.cod_empresa
																AND import_clientes.COD_EMPRESA = $cod_empresa
															SET import_clientes.COD_CLIENTE=clientes.COD_CLIENTE");

					//VINCULAR NA TABELA CLIENTES, POR CODIGO INTERNO
					mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes
															INNER JOIN clientes ON
																clientes.COD_CLIENTE=CAST(import_clientes.COD_INTERNO AS UNSIGNED)
																AND CAST(import_clientes.COD_INTERNO AS UNSIGNED) > 0
																AND import_clientes.COD_CLIENTE <= 0
																AND clientes.cod_empresa = import_clientes.cod_empresa
																AND import_clientes.COD_EMPRESA = $cod_empresa
															SET import_clientes.COD_CLIENTE=clientes.COD_CLIENTE");



					//AJUSTES DE IMPORTAÇÂO
					mysqli_query(connTemp($cod_empresa,""),"UPDATE import_clientes SET LOG_IMPORT='S',MSG_ERRO='' WHERE COD_CLIENTE > 0 AND LOG_INVALIDO <> 'S' AND import_clientes.COD_EMPRESA = $cod_empresa");

					unset($sql1);

					sleep(5);
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

					<div class="col-md-4">
						<div class="form-group">
							<label for="inputName" class="control-label">Nome e Tipo do Arquivo</label>
							<input type="text" class="form-control input-sm leitura2" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" readonly>
						</div>
					</div>

					<?php

						$sqlLinhas = "SELECT
						COUNT(IMPORT_CLIENTES.COD_IMPORTACAO) QTD,
						SUM(IF(IMPORT_CLIENTES.COD_CLIENTE > 0,1,0)) QTD_EXIST,
						COUNT(IMPORT_CLIENTES.COD_IMPORTACAO)-SUM(IF(IMPORT_CLIENTES.COD_CLIENTE > 0,1,0)) QTD_LINHAS_NOVOS,
						SUM(IF(IMPORT_CLIENTES.LOG_IMPORT='N',1,0)) AS QTD_LINHAS_ERRO
						FROM IMPORT_CLIENTES
						WHERE IMPORT_CLIENTES.COD_EMPRESA = $cod_empresa";
						$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
						$qrLinhas = mysqli_fetch_assoc($result);
						$qrLinhas['LINHAS'] = $qrLinhas['QTD'];
						
						$qrLinhas['QTD_LINHAS_NOVOS'] = ($qrLinhas['QTD_LINHAS_NOVOS'] < 0?0:$qrLinhas['QTD_LINHAS_NOVOS']);
					?>

					<div class="col-md-2">
						<div class="form-group">
							<label for="inputName" class="control-label">Qtd. Linhas</label>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?=$qrLinhas['QTD'];?>" readonly>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="inputName" class="control-label">Qtd. Exist.</label>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS_EXISTS" id="QTD_LINHAS_EXISTS" maxlength="45" value="<?=$qrLinhas['QTD_EXIST'];?>" readonly>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="inputName" class="control-label">Qtd. Novos</label>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS_NOVOS" id="QTD_LINHAS_NOVOS" maxlength="45" value="<?=$qrLinhas['QTD_LINHAS_NOVOS'];?>" readonly>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="inputName" class="control-label">Qtd. Inv&aacute;lidos</label>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS_ERRO" id="QTD_LINHAS_ERRO" maxlength="45" value="<?=$qrLinhas['QTD_LINHAS_ERRO'];?>" readonly>
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
			    				<th>Nome</th>
			    				<th>G&ecirc;nero</th>
								<th>Dt. Nasc.</th>
								<th>E-mail</th>
								<th>Msg. Erro</th>
			    			</tr>
			    		</thead>

			    		<tbody id="relConteudo">
					
							<?php

							$sqlProd = "
									  	 SELECT * FROM IMPORT_CLIENTES
										 WHERE COD_EMPRESA = $cod_empresa
										 ORDER BY MSG_ERRO DESC,NOME
									     LIMIT 10
									  ";

							$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));
							////fnEscreve($qrLinhas['LINHAS']);

							while($qrProd = mysqli_fetch_assoc($result)){
								?>
								<tr>
									<td><?php echo $qrProd['NOME']; ?></td>
									<td><?php echo $qrProd['GENERO']; ?></td>
									<td><?php echo $qrProd['DT_NASC']; ?></td>
									<td><?php echo $qrProd['EMAIL']; ?></td>
									<td><?php echo $qrProd['MSG_ERRO']; ?></td>
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
				<button type="submit" class="col-md-12 btn btn-primary next2">Próximo<i class="fas fa-arrow-right pull-right"></i></button>
			</div>
				

			<div class="push10"></div>

			<script>

				$.ajax({
					type: "GET",
					url: "../uploads/uploadImportClientes.php?acao=confirmar&id=<?php echo $cod_empresa; ?>&idx=<?=$cod_persona?>",
					success:function(data){
						$("#passo3").html(data);
					},
					error:function(){
						alert('Erro ao carregar...');
					}
				});

				var cont = 0;
				$('#loadMore').click(function(){
					
					cont +=10;

					if(cont >= "<?php echo $qrLinhas['LINHAS']; ?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
					}

					$.ajax({
						type: "GET",
						url: "../uploads/uploadImportClientes.php?acao=loadMore&itens="+cont+"&id=<?php echo $cod_empresa; ?>&idx=<?=$cod_persona?>",
						beforeSend:function(){	
							$('#loadMore').text('Carregando...');
						},
						success:function(data){
							$('#loadMore').text('Carregar Mais');
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
				

				<div class="col-md-3 col-md-offset-3">
					<input type="radio" id="ATUALIZAR" name="RADIO" checked value="ATUALIZAR">
					<label for="ATUALIZAR">Inserir clientes não-existentes</label>
				</div>

				<div class="col-md-3">
					<input type="radio" id="SUBSTITUIR" name="RADIO" value="SUBSTITUIR">
					<label for="SUBSTITUIR">Inserir clientes não-existentes e atualizar clientes existentes</label>
				</div>

			</div>
			<div class="push10"></div>
			<div class="row text-center">
				<div class="col-md-4 col-xs-offset-4">
					<div class="form-group">
						<label for="inputName" class="control-label">Informe o nome da Persona</label>
						<input type="text" class="form-control input-sm text-center" name="NOME_PERSONA" id="NOME_PERSONA" value="<?=$NOME_PERSONA?>" style="border-radius: 15px">
					</div>
				</div>
			</div>

		<?php
		/*
		$sql = "SELECT COUNT(0) QTD FROM personaclassifica
			WHERE COD_PERSONA=0".$cod_persona;
		$rs = mysqli_query(connTemp($cod_empresa,""),trim($sql));
		$linha = mysqli_fetch_assoc($rs);
		if ($linha["QTD"] > 0){
		?>
		
			<div class="push10"></div>
			<div class="alert alert-danger" role="alert">
				Esta persona já possui clientes. Os dados anteriores serão perdidos!
			</div>
			<div class="push10"></div>

		<?php }else{ ?>

			<div class="push100"></div>

		<?php } */?>

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
				if ($("#NOME_PERSONA").val() == ""){
					$.alert({
                        title: "Mensagem",
                        content: "Digite um nome para a Persona!",
                    });
				}else{
					$.ajax({
						type: "POST",
						url: "../uploads/uploadImportClientes.php?id=<?php echo $cod_empresa; ?>&idx=<?=$cod_persona?>",
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
				}
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
							 SELECT * FROM IMPORT_CLIENTES
							 WHERE COD_EMPRESA = $cod_empresa
							 ORDER BY MSG_ERRO DESC,NOME
						     LIMIT $limite,10
						 ";

				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd));

				while($qrProd = mysqli_fetch_assoc($result)){

								?>
								<tr>
									<td><?php echo $qrProd['NOME']; ?></td>
									<td><?php echo $qrProd['GENERO']; ?></td>
									<td><?php echo $qrProd['DT_NASC']; ?></td>
									<td><?php echo $qrProd['EMAIL']; ?></td>
									<td><?php echo $qrProd['MSG_ERRO']; ?></td>
								</tr>
								<?php
								}
								?>
			

<?php 

		break;

		default:

			if(isset($_POST['RADIO'])){

				$escolha = $_POST['RADIO'];


				$erros = "";
				//CHECA SE TEM UNIDADE DE VENDA COM CODIGO EXTERNO DUPLICADO
				$sqlLinhas = "SELECT COUNT(0) QTD,GROUP_CONCAT(COD_UNIVEND SEPARATOR ', ') COD_UNIVEND,COD_EXTERNO FROM UNIDADEVENDA
								WHERE UNIDADEVENDA.LOG_ESTATUS='S' AND UNIDADEVENDA.COD_EXTERNO IN (
									SELECT DISTINCT LOJA_CADASTRO FROM import_clientes
									WHERE IMPORT_CLIENTES.COD_EMPRESA = $cod_empresa
									  AND IMPORT_CLIENTES.LOG_IMPORT='S'
									)
								GROUP BY COD_EXTERNO
								HAVING COUNT(0) > 1";
				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
				while($qrLinha = mysqli_fetch_assoc($result)){
					$erros .= "Unidades de venda ".$qrLinha["COD_UNIVEND"]." possuem o mesmo código externo: ".$qrLinha["COD_EXTERNO"]."<br>";
				}
				if ($erros <> ""){
					$erros = "Ocorreu um erro ao inserir/atualizar clientes:<br>".$erros;
					$erros .= "Entre em contato com o SAC.";
					echo $erros;
					exit;
				}

				$sqlLinhas = "SELECT
				COUNT(IMPORT_CLIENTES.COD_IMPORTACAO) QTD,
				SUM(IF(IMPORT_CLIENTES.COD_CLIENTE > 0,1,0)) QTD_ATU,
				COUNT(IMPORT_CLIENTES.COD_IMPORTACAO)-SUM(IF(IMPORT_CLIENTES.COD_CLIENTE > 0,1,0)) AS QTD_INS
				FROM import_clientes
				WHERE LOG_IMPORT='S' AND IMPORT_CLIENTES.COD_EMPRESA = $cod_empresa";
				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas));
				$qrLinhas = mysqli_fetch_assoc($result);
				
				$sql = "INSERT INTO clientes
							(
								COD_EMPRESA,
								NOM_CLIENTE,
								COD_SEXOPES,
								TIP_CLIENTE,
								DAT_NASCIME,
								DES_EMAILUS,
								NUM_CGCECPF,
								NUM_CELULAR,
								NUM_TELEFON,
								DAT_CADASTR,
								DES_ENDEREC,
								NUM_ENDEREC,
								DES_COMPLEM,
								DES_BAIRROC,
								NUM_CEPOZOF,
								NOM_CIDADEC,
								COD_ESTADOF,
								COD_UNIVEND,
								LOG_FIDELIZADO,
								NUM_CARTAO,
								DIA,
								MES,
								ANO,
								IDADE
							)
							(
								SELECT
									import_clientes.COD_EMPRESA COD_EMPRESA,
									import_clientes.NOME NOM_CLIENTE,
									(CASE SUBSTR(UPPER(TRIM(IFNULL(import_clientes.GENERO,''))),1,1) WHEN 'M' THEN 1 WHEN 'F' THEN 2 ELSE NULL END) COD_SEXOPES,
									(CASE CHAR_LENGTH(import_clientes.CPFCNPJ) WHEN 11 THEN 'F' WHEN 15 THEN 'J' ELSE NULL END) TIP_CLIENTE,
									SUBSTR(import_clientes.DT_NASC,1,10) DAT_NASCIME,
									import_clientes.EMAIL DES_EMAILUS,
									import_clientes.CPFCNPJ NUM_CGCECPF,
									import_clientes.TEL_1 NUM_CELULAR,
									import_clientes.TEL_2 NUM_TELEFON,
									IF(import_clientes.cadastro!='',STR_TO_DATE(import_clientes.cadastro, '%d/%m/%Y'),NULL) DAT_CADASTR,
									import_clientes.ENDERECO DES_ENDEREC,
									import_clientes.NUMERO NUM_ENDEREC,
									import_clientes.COMPLEMENTO DES_COMPLEM,
									import_clientes.BAIRRO DES_BAIRROC,
									import_clientes.CEP NUM_CEPOZOF,
									import_clientes.CIDADE NOM_CIDADEC,
									import_clientes.ESTADO COD_ESTADOF,
									(SELECT COD_UNIVEND FROM UNIDADEVENDA WHERE UNIDADEVENDA.LOG_ESTATUS='S' AND UNIDADEVENDA.COD_EXTERNO = import_clientes.LOJA_CADASTRO) COD_UNIVEND,
									import_clientes.ADESAO LOG_FIDELIZADO,
									import_clientes.CPFCNPJ NUM_CARTAO,
									DAY(STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y')) DIA,
									MONTH(STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y')) MES,
									YEAR(STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y')) ANO,
									CASE
										WHEN IFNULL(import_clientes.DT_NASC,'')='' THEN NULL
										ELSE TIMESTAMPDIFF(YEAR, STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y'), NOW())
									END IDADE
								FROM import_clientes
								WHERE import_clientes.COD_CLIENTE <= 0
								AND IMPORT_CLIENTES.COD_EMPRESA = $cod_empresa AND IMPORT_CLIENTES.LOG_IMPORT='S'
							)";

					//mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die($sql);
					mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die("Erro ao inserir clientes. Entre em contato com o SAC.<div style='display:none'>$sql</div>");
					//fnEscreve($sql); 
				
				

				if($escolha != "ATUALIZAR" && $escolha != ""){

					sleep(6);

					$sql = "UPDATE clientes
								INNER JOIN IMPORT_CLIENTES ON clientes.COD_CLIENTE=import_clientes.COD_CLIENTE
								SET 
									clientes.NOM_CLIENTE=IF(import_clientes.NOME != '',import_clientes.NOME,clientes.NOM_CLIENTE),
									clientes.COD_SEXOPES=IF(import_clientes.GENERO != '',(CASE import_clientes.GENERO WHEN 'M' THEN 1 WHEN 'F' THEN 2 ELSE NULL END),clientes.COD_SEXOPES),
									clientes.TIP_CLIENTE=IF(import_clientes.CPFCNPJ != '',(CASE CHAR_LENGTH(import_clientes.CPFCNPJ) WHEN 11 THEN 'F' WHEN 15 THEN 'J' ELSE NULL END),clientes.TIP_CLIENTE),
									clientes.DAT_NASCIME=IF(import_clientes.DT_NASC != '',SUBSTR(import_clientes.DT_NASC,1,10),clientes.DAT_NASCIME),
									clientes.DES_EMAILUS=IF(import_clientes.EMAIL != '',import_clientes.EMAIL,clientes.DES_EMAILUS),
									clientes.NUM_CARTAO=IF(import_clientes.CPFCNPJ != '',import_clientes.CPFCNPJ,clientes.NUM_CARTAO),
									clientes.NUM_CELULAR=IF(import_clientes.TEL_1 != '',import_clientes.TEL_1,clientes.NUM_CELULAR),
									clientes.NUM_TELEFON=IF(import_clientes.TEL_2 != '',import_clientes.TEL_2,clientes.NUM_TELEFON),
									clientes.DES_ENDEREC=IF(import_clientes.ENDERECO != '',import_clientes.ENDERECO,clientes.DES_ENDEREC),
									clientes.NUM_ENDEREC=IF(import_clientes.NUMERO != '',import_clientes.NUMERO,clientes.NUM_ENDEREC),
									clientes.DES_COMPLEM=IF(import_clientes.COMPLEMENTO != '',import_clientes.COMPLEMENTO,clientes.DES_COMPLEM),
									clientes.DES_BAIRROC=IF(import_clientes.BAIRRO != '',import_clientes.BAIRRO,clientes.DES_BAIRROC),
									clientes.NUM_CEPOZOF=IF(import_clientes.CEP != '',import_clientes.CEP,clientes.NUM_CEPOZOF),
									clientes.NOM_CIDADEC=IF(import_clientes.CIDADE != '',import_clientes.CIDADE,clientes.NOM_CIDADEC),
									clientes.COD_ESTADOF=IF(import_clientes.ESTADO != '',import_clientes.ESTADO,clientes.COD_ESTADOF),
									clientes.COD_UNIVEND=IF(import_clientes.LOJA_CADASTRO != '',(SELECT COD_UNIVEND FROM UNIDADEVENDA WHERE UNIDADEVENDA.COD_EXTERNO = import_clientes.LOJA_CADASTRO),clientes.COD_UNIVEND),
									clientes.LOG_FIDELIZADO=IF(import_clientes.ADESAO != '',import_clientes.ADESAO,clientes.LOG_FIDELIZADO),
									clientes.DIA=IF(import_clientes.DT_NASC != '',DAY(STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y')),clientes.DIA),
									clientes.MES=IF(import_clientes.DT_NASC != '',MONTH(STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y')),clientes.MES),
									clientes.ANO=IF(import_clientes.DT_NASC != '',YEAR(STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y')),clientes.ANO),
									clientes.IDADE=IF(import_clientes.DT_NASC != '',TIMESTAMPDIFF(YEAR, STR_TO_DATE(SUBSTR(import_clientes.DT_NASC,1,10), '%d/%m/%Y'), NOW()),clientes.IDADE)
						    WHERE IMPORT_CLIENTES.COD_EMPRESA = $cod_empresa AND IMPORT_CLIENTES.LOG_IMPORT='S'";

					//mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die($sql);
					mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die("Erro ao atualizar clientes. Entre em contato com o SAC. <div style='display:none'>$sql</div>");
					

				}else{
					$qrLinhas['QTD_ATU']=0;
				}



				//VINCULAR NA TABELA CLIENTES, POR CPF/CNPJ
				mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes
														INNER JOIN clientes ON
															clientes.NUM_CGCECPF=CAST(import_clientes.CPFCNPJ AS UNSIGNED)
															AND CAST(import_clientes.CPFCNPJ AS UNSIGNED) > 0
															AND clientes.cod_empresa = import_clientes.cod_empresa
															AND import_clientes.COD_EMPRESA = $cod_empresa
														SET import_clientes.COD_CLIENTE=clientes.COD_CLIENTE");

				//VINCULAR NA TABELA CLIENTES, POR CODIGO INTERNO
				mysqli_query(connTemp($cod_empresa,""),"UPDATE IGNORE import_clientes
														INNER JOIN clientes ON
															clientes.COD_CLIENTE=CAST(import_clientes.COD_INTERNO AS UNSIGNED)
															AND CAST(import_clientes.COD_INTERNO AS UNSIGNED) > 0
															AND import_clientes.COD_CLIENTE <= 0
															AND clientes.cod_empresa = import_clientes.cod_empresa
															AND import_clientes.COD_EMPRESA = $cod_empresa
														SET import_clientes.COD_CLIENTE=clientes.COD_CLIENTE");


				sleep(1);

				$sql = "SELECT * FROM persona WHERE COD_PERSONA=0".$cod_persona;
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
				$qr = mysqli_fetch_assoc($arrayQuery);
				$des_icone = (@$qr["DES_ICONE"] == ""?"fa-file-import":$qr["DES_ICONE"]);
				$des_cor = (@$qr["DES_COR"] == ""?"E67E22":$qr["DES_COR"]);

				$sql = "CALL SP_ALTERA_PERSONA ('0".$cod_persona."',
								'".$cod_empresa."', 
								'S', 
								'".$_POST["NOME_PERSONA"]."',
								'',
								'".$des_icone."',
								'".$des_cor."',
								'Persona criada via rotina de importação',
								'N',
								'N',
								'".$cod_usucada."', 
								'0', 
								'".($cod_persona > 0?"ALT":"CAD")."'
								) ";

				// fnEscreve($sql);

				mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die("Erro ao criar persona. Entre em contato com o SAC.");

				if ($cod_persona <= 0){
					$sql = "SELECT MAX(COD_PERSONA) COD_PERSONA FROM PERSONA where COD_EMPRESA = '".$cod_empresa."' ";
					$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
					$qrPersosna = mysqli_fetch_assoc($arrayQuery);
					$cod_persona = $qrPersosna["COD_PERSONA"];
				}

				$sqlUpdt = "UPDATE PERSONA SET LOG_IMPORT='S', LOG_CONGELA = 'S' where COD_PERSONA=0".$cod_persona." AND COD_EMPRESA = '".$cod_empresa."' ";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlUpdt);

				$sql = "SELECT COUNT(0) QTD FROM personaregra WHERE COD_PERSONA=0".$cod_persona;
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
				$qr = mysqli_fetch_assoc($arrayQuery);
				$qtd = $qr["QTD"];
				if ($qtd <= 0){
					$sql = "INSERT INTO personaregra (COD_PERSONA) VALUES (0".$cod_persona.")";
					$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
				}

				$sqlRegra = "UPDATE PERSONAREGRA SET BL1_MASCULINO='S', BL1_FEMININO='S', BL1_LOG_FIDELIZADO = 'S' WHERE COD_PERSONA=".$cod_persona;
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlRegra);

				// $sql = "INSERT INTO personaclientes
				// 			(
				// 				COD_PERSONA,
				// 				COD_CAMPANHA,
				// 				COD_CLIENTE,
				// 				COD_EMPRESA
				// 			)
							
				// 				SELECT 0".$cod_persona." COD_PERSONA,0 COD_CAMPANHA,clientes.COD_CLIENTE,clientes.COD_EMPRESA
				// 				FROM import_clientes
				// 				INNER JOIN clientes ON (clientes.NUM_CGCECPF=IMPORT_CLIENTES.CPFCNPJ)
				// 				WHERE LOG_IMPORT = 'S'
				// 			";

				$sqlClass = "DELETE FROM personaclassifica WHERE COD_PERSONA=0".$cod_persona;
				mysqli_query(connTemp($cod_empresa,""),$sqlClass);

				$sqlClass = "INSERT IGNORE INTO personaclassifica
							(
								COD_PERSONA,
								COD_CLIENTE,
								COD_EMPRESA
							)
							
								SELECT 0".$cod_persona." COD_PERSONA, clientes.COD_CLIENTE,clientes.COD_EMPRESA
								FROM import_clientes
								INNER JOIN clientes ON (clientes.COD_CLIENTE=IMPORT_CLIENTES.COD_CLIENTE)
								WHERE LOG_IMPORT = 'S'
								 AND clientes.COD_CLIENTE NOT IN (SELECT COD_CLIENTE FROM personaclassifica WHERE COD_PERSONA=0".$cod_persona.")
							";

				mysqli_query(connTemp($cod_empresa,""),$sqlClass);



				$sql3 = "UPDATE PERSONA SET LOG_CONGELA = 'S', LOG_IMPORT = 'S'
						 WHERE COD_EMPRESA = $cod_empresa
						 AND COD_PERSONA = $cod_persona";

				mysqli_query(connTemp($cod_empresa,''),$sql3);

				// exit();

				?>

				<div class="push100"></div>

				<div class="row">
				
					<div class="col-md-2"></div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="inputName" class="control-label">Clientes Cadastrados</label>
							<input type="text" class="form-control input-sm leitura2" maxlength="45" value="<?=$qrLinhas['QTD_INS'];?>" readonly>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="inputName" class="control-label">Clientes Atualizados</label>
							<input type="text" class="form-control input-sm leitura2" maxlength="45" value="<?=$qrLinhas['QTD_ATU'];?>" readonly>
						</div>
					</div>

					<?php
					$sql = "SELECT COUNT(0) QTD FROM personaclassifica
						WHERE COD_PERSONA=0".$cod_persona;
					$rs = mysqli_query(connTemp($cod_empresa,""),trim($sql));
					$linha = mysqli_fetch_assoc($rs);
					?>
					<div class="col-md-3">
						<div class="form-group">
							<label for="inputName" class="control-label">Clientes na Persona</label>
							<input type="text" class="form-control input-sm leitura2" maxlength="45" value="<?=$linha['QTD'];?>" readonly>
						</div>
					</div>
				</div>

				<div class="push100"></div>

				<hr>

				<div class="col-md-10"></div>

				<div class="col-md-2">
					<a href="action.do?mod=<?php echo fnEncode(1035)."&id=".fnEncode($cod_empresa)."&idx=".fnEncode($cod_persona); ?>" class="col-md-12 btn btn-success concluir" target="_parent">Concluir</a>
				</div>
					

				<div class="push10"></div>

				<script>

				</script>

			<?php

			}

			 

		break;
		
	}	
?>
