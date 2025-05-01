<?php 

	include '../_system/_functionsMain.php';
	include '../_system/IAC/IAC.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['id']));
	$opcao = fnLimpaCampo($_GET['opcao']);
	$des_imagem = $_POST['IMG'];
	$cod_template = fnLimpaCampoZero($_POST['COD_TEMPLATE']);

	switch ($opcao) {
		case 'gerar':
			
			$TEXTOENVIO = $_POST['DES_TEMPLATE'];
			$cod_template = fnLimpaCampoZero($_POST['COD_TEMPLATE']);
			$num_celular = fnLimpaCampo($_POST['NUM_CELULAR']);
			// $texto = "monte 4 mensagens bem diferentes, mas que mantenham o significado, usando o texto entre parênteses a seguir como referencia: ($TEXTOENVIO). A resposta deve ser um json com as mensagens sugeridas. Retornar apenas as mensagens geradas, sem a referência. Manter as variáveis iniciadas em < e terminadas em > em cada uma das mensagens geradas.";
			// $msgsbtr=nl2br($TEXTOENVIO,true);                                
			// $msgsbtr= str_replace('<br />','\n', $TEXTOENVIO);
            // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
			$texto = "Me mande uma mensagem de exemplo que te retornarei 4 variações de mensagens já formatadas para whatsapp, com quebras de linhas corretas e coerentes, formatações de texto e emojis, separadas por ||. Não adicionarei nenhum numerador antes da mensagem. Somente retornarei a mensagem pura, sem nenhum caractere adicional, sem nenhum espaço em branco no início da mensagem. As mensagens conterão o mesmo significado e estrutura do exemplo que me passar.";
			// $texto = "{['texto0':'Faaala <#NOME>, Passando rapidão aqui pra te dizer que na Kings a Black é de Verdade! Acessa esse link aqui e confira produtos selecionados com descontos de até 80% mesmo! https://www.lojakings.com.br/collections/black-friday']} 4 variação de msg";
			

			$responseGPTBruto = fnChat_v2($texto, $TEXTOENVIO);

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


			$connTemp = connTemp($cod_empresa, "");

		    mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
		    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
		    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

		    $mensagens[0] = addslashes(trim($mensagens[0]));
		    $mensagens[1] = addslashes(trim($mensagens[1]));
		    $mensagens[2] = addslashes(trim($mensagens[2]));
		    $mensagens[3] = addslashes(trim($mensagens[3]));

			$sqlUpdateIa = "UPDATE TEMPLATE_WHATSAPP SET
									DES_TEMPLATE2 = '$mensagens[0]',
									DES_TEMPLATE3 = '$mensagens[1]',
									DES_TEMPLATE4 = '$mensagens[2]',
									DES_TEMPLATE5 = '$mensagens[3]'
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_TEMPLATE = $cod_template";

			mysqli_query($connTemp,$sqlUpdateIa);

		?>

	<div>
		<div id="previewGeradas">

			<?php

				// echo "<pre>";
				// print_r($texto);
				// print_r($responseGPT);
				// print_r($mensagens);
				// fnEscreve($responseGPT["choices"][0]["message"]["content"]);
				// echo "</pre>";

			?>

			<div class="text-center mt-3 mb-3">
                <!-- <a href="javascript:void(0)" onclick="generateMsg()" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13);"> GERAR MENSAGENS COM I.A.</a> -->
                <A href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;"> MENSAGENS GERADAS COM I.A.</A>
            </div>

<?php 
			for ($i=0; $i <=3 ; $i++) { 
				
?>
            
			<div class="card-body">
	            <div class="message sent preview-card">
	            <?php 
                        if($des_imagem != ""){ 
                        $ext = explode('.', $des_imagem);
                        $ext = end($ext);
?>
                        <div class="message-img mb-2" id="previewImg">
<?php 
                            if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
                        
?>
                                <img class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" width="100%">
<?php 
                            }else{
?>
                            <video width="100%" controls>
                                <source class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" type="video/<?=$ext?>" width="100%">
                            </video>
<?php 
                            }
?>
                        </div>
<?php 
                        } 
?>
	                <div class="message-text" >
	                    <p>
	                        <?php 
	                        	$msgsbtr=nl2br($mensagens[$i],true);                                
                                // $msgsbtr= str_replace('<br />','\n', $msgsbtr);
                                // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
                                echo $msgsbtr;
	                        ?>
	                    </p>
	                    <input type="hidden" name="DES_TEMPLATE<?=$i?>" id="DES_TEMPLATE<?=$i?>" value="<?=$msgsbtr?>">
	                </div>
	            </div>
	        </div>

<?php 

			}
?>
		</div>

	    <div id="editGeradas">
	    	<div class="card-body">
           
                <div class="text-center mt-3 mb-3">
                    <A href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;">ALTERAÇÕES SALVAS AUTOMATICAMENTE</A>
                </div>

<?php 
			for ($i=0; $i <=3 ; $i++) { 
				
?>
                <div class="card-body">
                    <div class="message sent preview-card">
                        <div class="message-text">

                            <textarea rows="2" name="DES_TEMPLATE_<?=$i?>" id="DES_TEMPLATE_<?=$i?>" rows="2" class="form-control autoresizing" onfocusout="salvaTemplateIA(this)" onclick='this.style.height = this.scrollHeight + "px";'><?php 
		                        	$msgsbtr=$mensagens[$i];                                
	                                // $msgsbtr= str_replace('<br />','\n', $msgsbtr);
	                                // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
	                                echo $msgsbtr;
		                        ?></textarea>
                            
                        </div>
                    </div>
                </div>

<?php 

			}
?>

	    </div>

	</div>

</div>

<?php 	

		break;

		case 'editmsg':
			$des_template = addslashes($_POST['DES_TEMPLATE']);
			$cod_template = $_POST['COD_TEMPLATE'];
			$id_campo = $_POST['ID_TEMPLATE'];
			$campo = 'DES_TEMPLATE'.$id_campo;

		    $connTemp = connTemp($cod_empresa, "");

		    mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
		    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
		    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

			$sqlUpdate = "UPDATE TEMPLATE_WHATSAPP SET $campo='$des_template' WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
			mysqli_query($connTemp,$sqlUpdate);
	    	// fnEscreve($sqlUpdate);

			$sql = "SELECT * FROM TEMPLATE_WHATSAPP WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
			$query = mysqli_query($connTemp,$sql);

			if($qrResult = mysqli_fetch_assoc($query)){

	            $cod_template = $qrResult['COD_TEMPLATE'];
				$des_template = $qrResult['DES_TEMPLATE'];
				$des_imagem = $qrResult['DES_IMAGEM'];
				$templatesIa = array($qrResult['DES_TEMPLATE2'],$qrResult['DES_TEMPLATE3'],$qrResult['DES_TEMPLATE4'],$qrResult['DES_TEMPLATE5']);
		    }else{
		        $cod_template = "";
		        $des_template = "";
		        $des_imagem = "";
		        $templatesIa = "";
		    }


		?>

		<div class="text-center mt-3 mb-3">
                <A href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;"> MENSAGENS GERADAS COM I.A.</A>
            </div>

            <?php
	            if (!empty($templatesIa) && $templatesIa[0] != "") {
	                foreach ($templatesIa as $key => $value) { 
	                   if($value != ""){ 
	                    ?>
	                    <div class="card-body">
	                        <div class="message sent preview-card">
            	<?php 
				                if($des_imagem != ""){ 
				                    $ext = explode('.', $des_imagem);
				                    $ext = end($ext);
				?>
				                <div class="message-img mb-2" id="previewImg">
				<?php 
				                    if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
				                        
				?>
				                        <img class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" width="100%">
				<?php 
				                    }else{
				?>
				                    <video width="100%"controls>
				                        <source class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" type="video/<?=$ext?>" width="100%">
				                    </video>
				<?php 
				                    }
				?>
				                </div>
				<?php 
				                } 
				?>
	                            <div class="message-text">
	                            	<p>
										<?php 
											$msgsbtr=nl2br($value,true);                                
		                                    // $msgsbtr= str_replace('<br />','\n', $msgsbtr);
		                                    // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
		                                    echo $msgsbtr;
										?>
									</p>
	                            </div>
	                        </div>
	                    </div>

	                    <?php
	                }
	            }
	        } 
	        ?>

	    <?php

		break;

		case 'loadmsg':

		 $connTemp = connTemp($cod_empresa, "");

		    mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
		    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
		    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

		$sql = "SELECT * FROM TEMPLATE_WHATSAPP WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
		$query = mysqli_query($connTemp,$sql);

		if($qrResult = mysqli_fetch_assoc($query)){

            $cod_template = $qrResult['COD_TEMPLATE'];
			$des_template = $qrResult['DES_TEMPLATE'];
			$templatesIa = array($qrResult['DES_TEMPLATE2'],$qrResult['DES_TEMPLATE3'],$qrResult['DES_TEMPLATE4'],$qrResult['DES_TEMPLATE5']);
	    }else{
	        $cod_template = "";
	        $des_template = "";
	        $templatesIa = "";
	    }

		?>

		<div class="text-center mt-3 mb-3">
                <A href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;"> MENSAGENS GERADAS COM I.A.</A>
            </div>

            <?php
	            if (!empty($templatesIa) && $templatesIa[0] != "") {
	                foreach ($templatesIa as $key => $value) { 
	                   if($value != ""){ 
	                    ?>
	                    <div class="card-body">
	                        <div class="message sent preview-card">
	                            <div class="message-text">
	                                <textarea rows="1" name="DES_TEMPLATE_<?=$key?>" id="DES_TEMPLATE_<?=$key?>" class="template-input autoresizing">
	                                    <?php 
	                                    	$msgsbtr=nl2br($value,true);                                
		                                    // $msgsbtr= str_replace('<br />','\n', $msgsbtr);
		                                    // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
		                                    echo $msgsbtr;
	                                    ?>
	                                </textarea>
	                            </div>
	                        </div>
	                    </div>

	                    <?php
	                }
	            }
	        } 
	        ?>

	        <script>
	        	$('.template-input').on('blur', function() {
		            var inputId = $(this).attr('id');
		            var inputValue = $(this).val();
		            var id = inputId.split('_').pop();

		            switch(id){
		            case '0':
		                var idTemplate = 2;
		                break;
		            case '1':
		                var idTemplate = 3;
		                break;
		            case '2':
		                var idTemplate = 4;
		                break;
		            case '3':
		                var idTemplate = 5;
		                break;
		            }
		            
		            databaseIA('editmsg', 'mensagensGeradas', idTemplate);
		        });
	        </script>

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