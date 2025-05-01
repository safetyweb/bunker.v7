<?php

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	//echo fnDebug('true');
	//fnEscreve('Entra no ajax');

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	$cod_empresa = fnLimpaCampoZero($_GET['id']);
	$cod_mapa = fnLimpaCampoZero($_GET['cod_mapa']);
	$acao = fnLimpaCampo(@$_GET['acao']);

	switch($acao){

		case "gravar": //Rotina de gravação da planilha na tabela 'temporária'

		$sql = "DELETE FROM MAPAS_TIPOS WHERE COD_EMPRESA = $cod_empresa AND LOG_CONFIRM='N'";
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

				/*
				Glossário do array da planilha:

				$row[0] = NOME;
				$row[1] = LOGRADOURO;
				$row[2] = ENDERECO;
				$row[3] = NUMERO;
				$row[4] = COMPLEMENTO;
				$row[5] = BAIRRO;
				$row[6] = CIDADE;
				$row[7] = ESTADO;
				$row[8] = CEP;
				*/

				$duplicado = 0;
				$ultimo_cod = 0;
				$sql1 = "";

				
				foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					
					foreach ($sheet->getRowIterator() as $row) {
						if($contador == 0){
							$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));
							////fnEscreve(count($colunas));
						}
						else if($contador != 0 && count($colunas) == 9){
							
							if (@$row[1] != ""){
								$end = FnGeoEnderec(fnLimpaCampo(trim($row[8])),fnLimpaCampo(trim($row[1]))." ".fnLimpaCampo(trim($row[2])),fnLimpaCampo(trim($row[3])),fnLimpaCampo(trim($row[5])),fnLimpaCampo(trim($row[6])),fnLimpaCampo(trim($row[7])));
								$insert .= "(
									'$cod_mapa',
									'[__cod_mapa_item__]',
									'$cod_empresa',
									'".fnLimpaCampo(trim($row[0]))."',
									'".fnLimpaCampo(trim($row[1]))."',
									'".fnLimpaCampo(trim($row[2]))."',
									'".fnLimpaCampo(trim($row[3]))."',
									'".fnLimpaCampo(trim($row[4]))."',
									'".fnLimpaCampo(trim($row[5]))."',
									'".fnLimpaCampo(trim($row[6]))."',
									'".fnLimpaCampo(trim($row[7]))."',
									'".fnLimpaCampo(trim($row[8]))."',
									'".@$end["latitude"]."',
									'".@$end["longitude"]."'
									),";
							}

						}else{
							echo 'A planilha deve conter exatamente 9 colunas: "Nome", "Logradouro", "Endereço", "Número", "Complemento", "Bairro", "Cidade", "Estado" e "CEP". Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
				}
				//fnEscreve($sql1);
				if($insert != ""){

					$insert = rtrim($insert,',');
					$f = explode(".",$file_name);
					
					$sql = "INSERT INTO MAPAS_TIPOS (COD_MAPA,COD_EMPRESA,NOM_MAPA_TIPO,NOM_ARQUIVO,LOG_CONFIRM)
													VALUES
													('$cod_mapa','$cod_empresa','".$f[0]."','$file_name','N')";
					//echo($sql);
					mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die("Erro ao inserir dados.");

					$sql = "SELECT MAX(COD_MAPA_TIPO) AS COD_MAPA_TIPO FROM MAPAS_TIPOS WHERE COD_MAPA='$cod_mapa' AND COD_EMPRESA='$cod_empresa' AND LOG_CONFIRM='N'";
					$result = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sql)));
					
					$insert = str_replace("[__cod_mapa_item__]",$result["COD_MAPA_TIPO"],$insert);
					$sql = "INSERT INTO MAPAS_TIPOS_ITENS(
										COD_MAPA,
										COD_MAPA_TIPO,
										COD_EMPRESA,
										NOM_NOME,
										DES_LOGRADOURO,
										DES_ENDEREC,
										NUM_ENDEREC,
										DES_COMPLEM,
										DES_BAIRROC,
										NOM_CIDADEC,
										COD_ESTADOF,
										NUM_CEPOZOF,
										LAT,
										LNG
										) VALUES $insert";
					//echo($sql);
					//exit;
					mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die("É possível que a ordem das colunas da planilha esteja incorreta.".$insert);

					unset($sql1);

					echo($duplicado);
					sleep(5);
				}else{

					echo "Não foram encontrados dados nesta planilha!";

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

					<div class="col-md-8">
						<div class="form-group">
							<label for="inputName" class="control-label">Nome e Tipo do Arquivo</label>
							<input type="text" class="form-control input-sm leitura2" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" readonly>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="inputName" class="control-label">Qtde de Linhas</label>
							<?php
								$sqlLinhas = "SELECT COUNT(0) AS LINHAS FROM mapas_tipos_itens I
												INNER JOIN mapas_tipos M ON (M.COD_MAPA_TIPO=I.COD_MAPA_TIPO)
												WHERE M.COD_EMPRESA = $cod_empresa AND M.LOG_CONFIRM='N'";
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas)) or die(mysqli_error());
								$qrLinhas = mysqli_fetch_assoc($result);
							?>
							<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?php echo $qrLinhas['LINHAS']; ?>" readonly>
						</div>
					</div>

					<div class="col-md-2" style="display:none;">
						<div class="form-group">
							<label for="inputName" class="control-label">Nro. de Itens Duplicados</label>
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
			    				<th>Nome</th>
			    				<th>Logradoouro</th>
			    				<th>Endere&ccedil;o</th>
			    				<th>N&ordm;</th>
								<th>Complemento</th>
								<th>Bairro</th>
								<th>Cidade</th>
								<th>Estado</th>
								<th>CEP</th>
			    			</tr>
			    		</thead>

			    		<tbody id="relConteudo">
					
							<?php

							$sqlBlk = "SELECT I.* FROM mapas_tipos_itens I
												INNER JOIN mapas_tipos M ON (M.COD_MAPA_TIPO=I.COD_MAPA_TIPO)
												WHERE M.COD_EMPRESA = $cod_empresa AND M.LOG_CONFIRM='N'
												LIMIT 20
									  ";
							$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlBlk)) or die(mysqli_error());
							//fnEscreve($sqlBlk);

							while($qrBlk = mysqli_fetch_assoc($result)){
								?>
								<tr>
									<td><?=$qrBlk['NOM_NOME']; ?></td>
									<td><?=$qrBlk['DES_LOGRADOURO']; ?></td>
									<td><?=$qrBlk['DES_ENDEREC']; ?></td>
									<td><?=$qrBlk['NUM_ENDEREC']; ?></td>
									<td><?=$qrBlk['DES_COMPLEM']; ?></td>
									<td><?=$qrBlk['DES_BAIRROC']; ?></td>
									<td><?=$qrBlk['NOM_CIDADEC']; ?></td>
									<td><?=$qrBlk['COD_ESTADOF']; ?></td>
									<td><?=$qrBlk['NUM_CEPOZOF']; ?></td>
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
					url: "../uploads/uploadImportEnderecos.php?acao=confirmar&id=<?php echo $cod_empresa; ?>&cod_mapa=<?=$cod_mapa?>",
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
						url: "../uploads/uploadImportEnderecos.php?acao=loadMore&itens="+cont+"&id=<?php echo $cod_empresa; ?>&cod_mapa=<?=$cod_mapa?>",
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

			$sql = "SELECT * FROM MAPAS_TIPOS WHERE COD_EMPRESA = $cod_empresa AND LOG_CONFIRM='N'";
			$rs = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sql)));
		?>

			<div class="row">

				<div class="col-md-12">
					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario_confirmacao">
													
						<fieldset>
							<legend>Preencha os dados</legend> 
							
								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome</label>
											<input type="text" class="form-control input-sm" name="NOM_MAPA_TIPO" id="NOM_MAPA_TIPO" value="<?=@$rs["NOM_MAPA_TIPO"]?>">
										</div> 
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Ícone</label><br/>
											<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" 
												data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" 
												data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
											</button>
											<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
										</div> 
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cor</label>
											<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>" required>															
										</div>
										<div class="help-block with-errors"></div>														
									</div>

									
								</div>
								
						</fieldset>
					
						</form>



					</div>
				</div>

			</div>

		<hr>

		<div class="col-md-2">
			<button class="col-md-12 btn btn-primary prev2"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
		</div>

		<div class="col-md-8"></div>

		<div class="col-md-2">
			<button class="col-md-12 btn btn-primary next3">Confirmar<i class="fas fa-check pull-right"></i></button>
		</div>
			

		<div class="push10"></div>

	
		<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
		
		<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
		<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
		
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

		<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
		<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
		
		<script>
			$(document).ready( function() {				
				//color picker
				$('.pickColor').minicolors({
					control: $(this).attr('data-control') || 'hue',				
					theme: 'bootstrap'
				});

				//capturando o ícone selecionado no botão
				$('#btniconpicker').on('change', function(e) {
					$('#DES_ICONE').val(e.icon);
					//alert($('#DES_ICONE').val());
				});

			});
			
			$('.next3').click(function(){
				$.ajax({
					type: "POST",
					url: "../uploads/uploadImportEnderecos.php?id=<?php echo $cod_empresa; ?>&cod_mapa=<?=$cod_mapa?>",
					data: $('#formulario_confirmacao').serialize(),
					beforeSend:function(){
						$('#passo3').hide();
						$('#passo4').show();
						$("#passo4").html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
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

				//fnEscreve($limite);

				$sqlBlk = "SELECT I.* FROM mapas_tipos_itens I
									INNER JOIN mapas_tipos M ON (M.COD_MAPA_TIPO=I.COD_MAPA_TIPO)
									WHERE M.COD_EMPRESA = $cod_empresa AND M.LOG_CONFIRM='N'
									LIMIT $limite,20
						  ";

				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlBlk)) or die(mysqli_error());

				while($qrBlk = mysqli_fetch_assoc($result)){
					?>
					<tr>
						<td><?=$qrBlk['NOM_NOME']; ?></td>
						<td><?=$qrBlk['DES_LOGRADOURO']; ?></td>
						<td><?=$qrBlk['DES_ENDEREC']; ?></td>
						<td><?=$qrBlk['NUM_ENDEREC']; ?></td>
						<td><?=$qrBlk['DES_COMPLEM']; ?></td>
						<td><?=$qrBlk['DES_BAIRROC']; ?></td>
						<td><?=$qrBlk['NUM_CEPOZOF']; ?></td>
						<td><?=$qrBlk['NOM_CIDADEC']; ?></td>
						<td><?=$qrBlk['COD_ESTADOF']; ?></td>
					</tr>
					<?php
					}
					?>
			

<?php 

		break;

		default:


			$nom_mapa_tipo = fnLimpaCampo($_POST['NOM_MAPA_TIPO']);
			$des_icone = fnLimpaCampo($_POST['DES_ICONE']);
			$des_cor = fnLimpaCampo($_POST['DES_COR']);

			$sql = "UPDATE mapas_tipos SET
						NOM_MAPA_TIPO='$nom_mapa_tipo',
						DES_ICONE='$des_icone',
						DES_COR='#$des_cor',
						LOG_CONFIRM='S'
					WHERE COD_EMPRESA = $cod_empresa AND COD_MAPA= $cod_mapa AND LOG_CONFIRM='N'
					";

			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());

			?>

			<div class="col-md-4 text-center">
				<h4>Lista importada com sucesso.</h4>
			</div>

			<?php

		break;
		
	}	
?>
