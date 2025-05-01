<?php include "_system/_functionsMain.php"; 

	//echo fnDebug('true');

	$codEmpresa = fnLimpacampo($_GET['cod_empresa']);
	$opcao = fnLimpacampo($_GET['opcao']);
	$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
	$buscaAjx4 = fnLimpacampo($_GET['ajx4']);
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

	//fnescreve($opcao);

	//tabela do update
	switch ($opcao) {
		// case 'popularComboBloco':
		// 	$cod_registr = fnLimpacampoZero($_GET['cod_registr']);
		// 	$cod_pesquisa = fnLimpacampoZero($_GET['cod_pesquisa']);
		// 	$sql = "SELECT COD_REGISTR, DES_PERGUNTA FROM MODELOPESQUISA WHERE modelopesquisa.COD_EXCLUSA is null and COD_REGISTR != $cod_registr and COD_TEMPLATE = $cod_pesquisa";
		// 	$arrayQuery = mysqli_query(connTemp($codEmpresa,''),$sql) or die(mysqli_error());		
		// 	while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {																			
		// 		echo "<option value='".$qrLista['COD_REGISTR']."'>".ucfirst($qrLista['DES_PERGUNTA']). "</option>"; 
		// 	}	
		// break;		
		case 'alterarBloco':
			$pergunta = fnLimpacampo($_GET['pergunta']);
			$des_imagem = fnLimpacampo($_GET['des_imagem']);
			$cod_registr = fnLimpacampo($_GET['cod_registr']);
		
			$sql = "UPDATE MODELOPESQUISA SET 
					DES_IMAGEM = '$des_imagem', 
					DES_PERGUNTA = '$pergunta' 
					WHERE COD_REGISTR = $cod_registr;";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($codEmpresa,''),$sql) or die(mysqli_error());
		break;		
		case 0://Inclui modelo Template
			$sql = "CALL SP_ALTERA_MODELO_PESQUISA(0, '".$buscaAjx3."', '".$codEmpresa."', '".$buscaAjx4."', ' ', '".$cod_usucada."', 'CAD' )";

			$sql = "INSERT INTO MODELOPESQUISA(
								COD_EMPRESA,
								COD_TEMPLATE,
								COD_BLPESQU,
								COD_USUCADA,
								DAT_CADASTR
								) VALUES(
								$codEmpresa,
								$buscaAjx3,
								$buscaAjx4,
								$cod_usucada,
								Now()
								)";
			//fnEscreve($sql);
			mysqli_query(connTemp($codEmpresa,""),trim($sql)) or die(mysqli_error());

			
			$tipoBloco = '';
			if($buscaAjx4 == 1){
				$tipoBloco = 'Digite aqui seu <b>texto</b>';	
			}else if($buscaAjx4 == 2){
				$tipoBloco = 'Digite aqui sua <b>pergunta</b>';
			}else if($buscaAjx4 == 3){
				$tipoBloco = 'Saldo de Pontos';
			}else if($buscaAjx4 == 4){
				$tipoBloco = 'Imagem';
			}

			$sql = "SELECT MAX(COD_REGISTR) AS COD_REGISTR FROM MODELOPESQUISA WHERE COD_USUCADA = $cod_usucada AND COD_EMPRESA = $codEmpresa";
			//fnEscreve($sql);
			$retorno = mysqli_query(connTemp($codEmpresa,""),trim($sql)) or die(mysqli_error());
			$row=mysqli_fetch_assoc($retorno);
				
			$sql = "UPDATE MODELOPESQUISA SET DES_PERGUNTA = '$tipoBloco' WHERE COD_REGISTR = $row[COD_REGISTR]";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($codEmpresa,''),$sql) or die(mysqli_error());			
			
			echo $row['COD_REGISTR'];
		break;		
		case 1:// TEXTO INFORMATIVO
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10 blocoTexto">
						<label for="inputName" class="control-label">Digite aqui seu <b>texto</b></label>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break;     
		case 2:// PERGUNTA
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10 blocoPergunta">
						<label for="inputName" class="control-label">Digite aqui sua <b>pergunta</b></label>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break; 				
		case 3:// SALDO DE PONTOS
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10">
						<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
						<h6>Número Cartão: 1234 5678 9012 3456</h6>
						<h6>Saldo: R$ 0,18</h6>
						<h6>31/05/2017</h6>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break; 				
		case 4: // IMAGEM
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10">
						<div class="div-imagem">
							<div class="imagemTicket">
								<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
								<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR'];?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break;		
		case 5:// AVALIAÇÃO
		?>
		<center class="bloco">
			<div class="row">
				<div class="col-md-10 blocoAvaliacaoComentado addBox">
					<h5>Digite a descrição aqui</h5>
					<input type="hidden" class="des_pergunta" value="">												
					<input type="hidden" class="tip_bloco" value="estrela">												
					<input type="hidden" class="num_quantid" value="10">												
					<input type="hidden" class="log_condicoes" value='[]'>												
					<fieldset class="rating rate10">
					<?php
						$contador = 0;
						while ($contador < 10) {
							?>
								<input type="radio" name="rating" value="5" /><label class="star<?php echo $contador;?> estrelaType full" for="star"></label>
							<?php
							$contador++;
						}																		
					?>
					</fieldset>	
				</div>
				<div class="col-md-2">
					<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
				</div>																	
			</div>															
		</center>
		<hr class="divisao"/>																
		<?php
		break;	
		case 6:// LOGIN
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10" style="padding: 0 0 0 50px;">
						<header>
							<p class="lead">Faça seu login para responder nossas pesquisas!</p>
						</header>
						<div class="row">
							<div class="col-md-12">
								<input type="text" id="cpf" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
								<div class="push10"></div>
								<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
								<div class="push10"></div>
								<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break;	
		case 7:// LOGIN COM SENHA
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10" style="padding: 0 0 0 50px;">
						<header>
							<p class="lead">Faça seu login para responder nossas pesquisas!</p>
						</header>
						<div class="row">
							<div class="col-md-12">
								<input type="text" id="cpf" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
								<div class="push10"></div>
								<input type="password" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha">
								<div class="push10"></div>
								<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
								<div class="push10"></div>
								<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break;
		case 8:// SMART LOGIN
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10">
						<div class="col-md-4 text-center"><span class="fa fa-envelope"></span></div>
						<div class="col-md-4 text-center"><span class="fa fa-phone"></span></div>
						<div class="col-md-4 text-center"><span class="fa fa-user"></span></div>
					</div>
					<div class="col-md-2">
						<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
					</div>																	
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break;		
		case 99:
			// $sql = "CALL SP_ALTERA_MODELO_PESQUISA ('".$buscaAjx3."', 0, 0, 0, ' ', '".$cod_usucada."', 'EXC' )";
			$sql = "UPDATE MODELOPESQUISA SET COD_EXCLUSA = $cod_usucada, DAT_EXCLUSA = Now() WHERE COD_REGISTR = $buscaAjx3";
			// fnEscreve($sql);
			mysqli_query(connTemp($codEmpresa,""),trim($sql)) or die(mysqli_error());
		break;

	}
?>

