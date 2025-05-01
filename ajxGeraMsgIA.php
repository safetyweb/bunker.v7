<?php 

	include '_system/_functionsMain.php';
	include '_system/IAC/IAC.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['id']));
	$opcao = fnLimpaCampo($_GET['opcao']);

	switch ($opcao) {
		case 'gerar':
			
			$TEXTOENVIO = $_POST['DES_TEMPLATE'];
			$cod_template = fnLimpaCampoZero($_POST['COD_TEMPLATE']);
			$num_celular = fnLimpaCampo($_POST['NUM_CELULAR']);
			// $texto = "monte 4 mensagens bem diferentes, mas que mantenham o significado, usando o texto entre parênteses a seguir como referencia: ($TEXTOENVIO). A resposta deve ser um json com as mensagens sugeridas. Retornar apenas as mensagens geradas, sem a referência. Manter as variáveis iniciadas em < e terminadas em > em cada uma das mensagens geradas.";
			$msgsbtr=nl2br($TEXTOENVIO,true);                                
			$msgsbtr= str_replace('<br />',' \n ', $msgsbtr);
			$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);
			$texto = "'$msgsbtr' || variação || variação ... variação sendo variações da mensagem separadas por ||. 4 variações";
			// $texto = "{['texto0':'Faaala <#NOME>, Passando rapidão aqui pra te dizer que na Kings a Black é de Verdade! Acessa esse link aqui e confira produtos selecionados com descontos de até 80% mesmo! https://www.lojakings.com.br/collections/black-friday']} 4 variação de msg";
			// fnEscreve($texto);

			$responseGPTBruto = fnChat($texto);

			$responseGPT = json_decode($responseGPTBruto,true);
			$mensagensBrutas = $responseGPT["choices"][0]["message"]["content"];
			$mensagens = explode('||', $mensagensBrutas);
			// $i=1;
			// $msg = array();

			// $result = call_user_func_array('array_merge', $mensagens);
			// $msg = array_values($mensagens);


			// foreach ($mensagens as $mensagem => $value) {
			// 	fnEscreve($mensagem);
			// 	fnEscreve($value);
			// 	array_push($msg, $value);
			// }

			// echo "<pre>";
			// print_r($texto);
			// print_r($responseGPT);
			// print_r($mensagens);
			// fnEscreve($responseGPT["choices"][0]["message"]["content"]);
			// echo "</pre>";

		?>

		<div class="push10"></div>

		<h4>Templates Alternativas</h4>

		<div class="row" style="background: url('media/whats_bg.jpg') center no-repeat; margin-left: 0px; margin-right: 0px; border-radius: 7px; background-size: cover;">
										
			<div class="col-sm-9 sb13">
				<div class="form-group">
					<textarea type="text" class="form-control input-sm whatsapp" rows="4"  name="DES_TEMPLATE2" id="DES_TEMPLATE2"><?php echo trim(str_replace("'", '', $mensagens[0]));?></textarea>
				</div>
			</div>

			<div class="push10"></div>
			
			<div class="col-sm-9 sb13">
				<div class="form-group">
					<textarea type="text" class="form-control input-sm whatsapp" rows="4"  name="DES_TEMPLATE3" id="DES_TEMPLATE3"><?php echo trim(str_replace("'", '', $mensagens[1]));?></textarea>
				</div>
			</div>

			<div class="push10"></div>
			
			<div class="col-sm-9 sb13">
				<div class="form-group">
					<textarea type="text" class="form-control input-sm whatsapp" rows="4"  name="DES_TEMPLATE4" id="DES_TEMPLATE4"><?php echo trim(str_replace("'", '', $mensagens[2]));?></textarea>
				</div>
			</div>						

			<div class="push10"></div>
			
			<div class="col-sm-9 sb13">
				<div class="form-group">
					<textarea type="text" class="form-control input-sm whatsapp" rows="4"  name="DES_TEMPLATE5" id="DES_TEMPLATE5"><?php echo trim(str_replace("'", '', $mensagens[3]));?></textarea>
				</div>
			</div>

			<div class="push10"></div>				

		</div>

<?php 

		break;
		
		default:

			$cod_template = fnLimpaCampoZero(fnDecode($_POST['pk']));
			$campo = fnLimpaCampo($_POST['name']);
			$valor = addslashes($_POST['value']);

			// if (strpos($valor, ',') !== false) {
			//     $valor = fnValorSql($valor);
			// }

			// fnEscreve($cod_empresa);
			// fnEscreve($campo);
			// fnEscreve($valor);


			$sql = "UPDATE TEMPLATE_WHATSAPP SET $campo='$valor' WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
			// fnEscreve($sql);
			mysqli_query(conntemp($cod_empresa,""),$sql);
			echo $valor;
			
		break;
	}

?>