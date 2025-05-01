<?php 

include_once 'header.php'; 
$tituloPagina = "Cadastro";
include_once "navegacao.php"; 

// unset($_SESSION['usuario']);
// $_SESSION['usuario'] = "";

$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
$log_termos = $qrLog['LOG_TERMOS'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

// if(mysqli_num_rows($arrayControle) == 0){

// 	$sqlIns = "INSERT INTO CONTROLE_TERMO(
// 						      COD_EMPRESA,
// 						      TXT_ACEITE,
// 							  TXT_COMUNICA,
// 							  LOG_SEPARA,
// 							  COD_USUCADA
// 						   ) VALUES(
// 						   	  $cod_empresa,
// 						   	  'Estou ciente e de acordo com os termos, e desejo me cadastrar:',
// 						   	  'Comunicação',
// 						   	  'N',
// 						   	  $_SESSION[SYS_COD_USUARIO]
// 						   )";

// 	mysqli_query(connTemp($cod_empresa,''),$sqlIns);

// 	$sqlContole = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// 	$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

// }

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

?>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="cadastro_V2.do?key=<?=$_GET[key]?>&t=<?=$rand?>">
	
    <div class="container">

		<div class="row">
			


					<div class="push20"></div>
					<div class="push50"></div>
					
					<?php

						$sqlCampos = "SELECT COD_CHAVECO, LOG_BLOQUEIAPJ FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

						$arrayCampos = mysqli_query($connAdm->connAdm(),$sqlCampos);

						// echo($sqlCampos);

						$lastField = "";

						$qrCampos = mysqli_fetch_assoc($arrayCampos);

						switch ($qrCampos[COD_CHAVECO]) {

							case 2:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cartão/CPF</label>
											<input type="text" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo2 int" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" required>
											<div class="help-block with-errors">Caso nao possua um número de cartão válido do programa, digite o seu CPF (somente números)</div>
										</div>
									</div>

									<input type="hidden" class="campo1" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php

							break;

							case 3:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Celular</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo2 sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo1" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php
								
							break;

							case 4:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código Externo</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo2" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo1" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php
								
							break;

							case 5:

								?>
									<p>Caso nao possua um número de cartão válido do programa, digite o seu CPF (somente números)</p>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">CPF/CNPJ</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push10"></div>
									<center>
										<p class="text-muted" style="padding-bottom: 0;margin-bottom: 0;">-- OU --</p>
									</center>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cartão</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo2" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" data-error="ou este" maxlenght="10" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php
								
							break;

							case 6:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">CPF/CNPJ</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nascimento</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo2 data" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Celular</label>
											<input type="tel" style="font-size: 36px;" class="form-control input-hg input-lg text-center campo3 sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label required">Email</label>
											<input type="email" class="form-control input-hg input-sm campo4" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php
								
							break;							
							
							default:

								$label = "CPF/CNPJ";
								$charLenght = "18";

								if($qrCampos['LOG_BLOQUEIAPJ'] == 'S'){
									$label = "CPF";
									$charLenght = "14";
								}

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required"><?=$label?></label>
											<input type="tel" style="font-size: 38px;" class="form-control input-hg input-lg text-center campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" maxlength="<?=$charLenght?>" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo2" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php

							break;

						}

					?>

				<div class="push20"></div>

				<div class="col-xs-12">
					<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn validaCPF" tabindex="5"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
				</div>

				<div class="push30"></div>

				<center><span class="notice" style="display: none;">Cliente já cadastrado. <b><a href="app.do?key=<?=$_GET[key]?>&t=<?=$rand?>">Fazer login</a></b></span></center>

				<div class="push50"></div>

				

		</div>
        

    </div> <!-- /container -->

</form>

<?php include 'footer.php'; ?>

<link href="libs/jquery-confirm.min.css" rel="stylesheet"/>
<script src="libs/jquery-confirm.min.js"></script>

<script>

	let bloqueiapj =" <?=$log_bloqueiapj?>",
		masks = ['000.000.000-000', '00.000.000/0000-00'];

	if(bloqueiapj == "S"){
		masks = ['000.000.000-000'];
	}

	$(function(){

		if($('.cpfcnpj').val() != undefined){
			mascaraCpfCnpj($('.cpfcnpj'));
		}

		$('.validaCPF').click(function(e){

			e.preventDefault();

			var campo1 = $(".campo1").val(),
				campo2 = $(".campo2").val(),
				campo3 = $(".campo3").val(),
				campo4 = $(".campo4").val(),
				pCPF = $('.cpfcnpj').val(),
				valida = 1;

				if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

					if(campo1 != ""){

						if(!valida_cpf_cnpj($('.cpfcnpj').val())){

							$.alert({
								title: 'Atenção!',
								content: 'CPF/CNPJ digitado é inválido!',
							});	

							valida = 0;

						}

					}

				}else{

					$.alert({
						title: 'Atenção!',
						content: 'Pelo menos um dado deve ser informado!',
					});

					valida = 0;

				}

				if(valida == 1){

					$.ajax({
		                type: "POST",                
		                url: "ajxLogin.php?tp=c",
		                data: { CPF:pCPF, senha:'', codEmpresa: "<?=$cod_empresa?>" },
		                success: function(msg) {
		                	console.log(msg);
		                    if(msg.indexOf('1') > -1){
		                        $(".notice").show();
		                    }else{
		                        document.getElementById("formulario").submit();
		                    }
		                }
		            });

				}

		});

	});
	
	function mascaraCpfCnpj(cpfCnpj){
		var optionsCpfCnpj = {
			onKeyPress: function (cpf, ev, el, op) {
					mask = (cpf.length >= 15) ? masks[1] : masks[0];
				cpfCnpj.mask(mask, op);
			}
		}	

		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
			
		cpfCnpj.mask(mask, optionsCpfCnpj);		
	}

	function verifica_cpf_cnpj ( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');

		// Verifica CPF
		if ( valor.length === 11 ) {
			return 'CPF';
		} 
		
		// Verifica CNPJ
		else if ( valor.length === 14 ) {
			return 'CNPJ';
		} 
		
		// Não retorna nada
		else {
			return false;
		}
		
	}

	function valida_cpf_cnpj ( valor ) {

		// Verifica se é CPF ou CNPJ
		var valida = verifica_cpf_cnpj( valor );

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');


		// Valida CPF
		if ( valida === 'CPF' ) {
			// Retorna true para cpf válido
			return valida_cpf( valor );
		} 
		
		// Valida CNPJ
		else if ( valida === 'CNPJ' ) {
			// Retorna true para CNPJ válido
			return valida_cnpj( valor );
		} 
		
		// Não retorna nada
		else {
			return false;
		}
	}	
	
	function valida_cpf( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');


		// Captura os 9 primeiros dígitos do CPF
		// Ex.: 02546288423 = 025462884
		var digitos = valor.substr(0, 9);

		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
		var novo_cpf = calc_digitos_posicoes( digitos );

		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
		var novo_cpf = calc_digitos_posicoes( novo_cpf, 11 );

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		if ( novo_cpf === valor ) {
			// CPF válido
			return true;
		} else {
			// CPF inválido
			return false;
		}
		
	} // valida_cpf

	/*
	 valida_cnpj
	 
	 Valida se for um CNPJ
	 
	 @param string cnpj
	 @return bool true para CNPJ correto
	*/
	function valida_cnpj ( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');

		
		// O valor original
		var cnpj_original = valor;

		// Captura os primeiros 12 números do CNPJ
		var primeiros_numeros_cnpj = valor.substr( 0, 12 );

		// Faz o primeiro cálculo
		var primeiro_calculo = calc_digitos_posicoes( primeiros_numeros_cnpj, 5 );

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		var segundo_calculo = calc_digitos_posicoes( primeiro_calculo, 6 );

		// Concatena o segundo dígito ao CNPJ
		var cnpj = segundo_calculo;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ( cnpj === cnpj_original ) {
			return true;
		}
		
		// Retorna falso por padrão
		return false;
		
	} // valida_cnpj	
	
	function calc_digitos_posicoes( digitos, posicoes = 10, soma_digitos = 0 ) {

		// Garante que o valor é uma string
		digitos = digitos.toString();

		// Faz a soma dos dígitos com a posição
		// Ex. para 10 posições:
		//   0    2    5    4    6    2    8    8   4
		// x10   x9   x8   x7   x6   x5   x4   x3  x2
		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
		for ( var i = 0; i < digitos.length; i++  ) {
			// Preenche a soma com o dígito vezes a posição
			soma_digitos = soma_digitos + ( digitos[i] * posicoes );

			// Subtrai 1 da posição
			posicoes--;

			// Parte específica para CNPJ
			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
			if ( posicoes < 2 ) {
				// Retorno a posição para 9
				posicoes = 9;
			}
		}

		// Captura o resto da divisão entre soma_digitos dividido por 11
		// Ex.: 196 % 11 = 9
		soma_digitos = soma_digitos % 11;

		// Verifica se soma_digitos é menor que 2
		if ( soma_digitos < 2 ) {
			// soma_digitos agora será zero
			soma_digitos = 0;
		} else {
			// Se for maior que 2, o resultado é 11 menos soma_digitos
			// Ex.: 11 - 9 = 2
			// Nosso dígito procurado é 2
			soma_digitos = 11 - soma_digitos;
		}

		// Concatena mais um dígito aos primeiro nove dígitos
		// Ex.: 025462884 + 2 = 0254628842
		var cpf = digitos + soma_digitos;

		// Retorna
		return cpf;
		
	} // calc_digitos_posicoes	
	
</script>