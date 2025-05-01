<?php 

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

<form data-toggle="validator" role="form2" method="post" id="formulario" action="cadastro_V2.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>">
	
    <div class="container">

		<div class="row">
			


					<div class="push20"></div>
					<div class="push50"></div>
					
					<?php

						$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

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

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">CPF/CNPJ</label>
											<input type="tel" style="font-size: 38px;" class="form-control input-hg input-lg text-center campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
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

					
				<div class="push50"></div>

				

		</div>
        

    </div> <!-- /container -->

</form>

<script>

	if($('.cpfcnpj').val() != undefined){
		mascaraCpfCnpj($('.cpfcnpj'));
	}
	
	function mascaraCpfCnpj(cpfCnpj){
		var optionsCpfCnpj = {
			onKeyPress: function (cpf, ev, el, op) {
				var masks = ['000.000.000-000', '00.000.000/0000-00'],
					mask = (cpf.length >= 15) ? masks[1] : masks[0];
				cpfCnpj.mask(mask, op);
			}
		}	

		var masks = ['000.000.000-000', '00.000.000/0000-00'];
		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
			
		cpfCnpj.mask(mask, optionsCpfCnpj);		
	}

	$('.validaCPF').click(function(e){

		var campo1 = $(".campo1").val(),
			campo2 = $(".campo2").val(),
			campo3 = $(".campo3").val(),
			campo4 = $(".campo4").val();

			if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

				if(campo1 != ""){

					if(!valida_cpf_cnpj($('.cpfcnpj').val())){

						e.preventDefault();
						parent.$.alert({
							title: 'Atenção!',
							content: 'CPF/CNPJ digitado é inválido!',
						});	

					}

				}

			}else{

				e.preventDefault();
				parent.$.alert({
					title: 'Atenção!',
					content: 'Pelo menos um dado deve ser informado!',
				});

			}

	});
	
</script>