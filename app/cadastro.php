<?php
include 'header.php'; 
$tituloPagina = "Cadastro";
include "navegacao.php"; 
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';
include_once '../totem/funWS/saldo.php';
//echo fnDebug('true');
if(isset($_POST["c1"])){
	$cpf = $_POST["c1"];
}else{
	if(isset($_SESSION["usuario"])){
		$cpf = $_SESSION["usuario"];
	}
}

$sql = "SELECT * FROM CLIENTES WHERE NUM_CGCECPF = '$cpf' AND COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1";
// echo($sql);
$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

$cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
$num_cgcecpf = $qrCli['NUM_CGCECPF'];
$nom_cliente = $qrCli['NOM_CLIENTE'];
$dat_nascime = $qrCli['DAT_NASCIME'];
$des_emailus = $qrCli['DES_EMAILUS'];
$cod_sexopes = $qrCli['COD_SEXOPES'];
$num_celular = $qrCli['NUM_CELULAR'];
$cod_univend = $qrCli['COD_UNIVEND'];

// echo($cod_cliente);

if($qrCli['LOG_SMS'] == 'S'){
	$checkSms = "checked";
}else{
	$checkSms = "";		
}

if($qrCli['LOG_EMAIL'] == 'S'){
	$checkEmail = "checked";
}else{
	$checkEmail = "";		
}

// $arrayCampos = explode(";", $key);

// $dadoslogin = array(
// 	'0'=>$arrayCampos[0],
// 	'1'=>$arrayCampos[1],
// 	'2'=>$arrayCampos[3],
// 	'3'=>'maquina',
// 	'4'=>$arrayCampos[2]
// );


$hashLocal = mt_rand();	

if( $_SERVER['REQUEST_METHOD']=='POST' )
{

	$cpf = fnLimpaDoc($_REQUEST['c1']);

	//fnEscreve($cpf); 

	$request = md5( implode( $_POST ) );
	
	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;                
                      
                       
                if ($opcao != ''){
			
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';						  
			
		}  

	}
            
    
}

if($cod_cliente == 0){

	if(strlen($cpf)=='11')
	{    
		//fnEscreve('11');
	    $buscaconsumidor = fnconsulta($cpf, $dadoslogin);
	    
	}else{
		//fnEscreve('else');
	    $buscaconsumidor = fnconsultacnpf($cpf, $dadoslogin); 
	    
	}

	if($buscaconsumidor['localizacaocliente']=='13'){

      $cpf = $cpf;

    }else{

	    if($buscaconsumidor['cpf']=='00000000000'){ 

	    	$cpf=$cpf;

	    }else{

	      $cpf=$buscaconsumidor['cpf'];

	    } 

	}

	$num_cgcecpf = $cpf;
	$nom_cliente = $buscaconsumidor['nome'];
	$dat_nascime = $buscaconsumidor['datanascimento'];
	$des_emailus = $buscaconsumidor['email'];
	$cod_sexopes = $buscaconsumidor['sexo'];
	$num_celular = $buscaconsumidor['telcelular'];
	$checkSms = "";
	$checkEmail = "";
	$cod_univend = "";
	$txtBtn = "Cadastrar";

}else{
	$txtBtn = "Atualizar";
}
	// echo '<pre>';
	// print_r($buscaconsumidor);
	// echo '</pre>';
?>

<style>
	#COD_UNIVEND_chosen {
		font-size: 22px;
		margin-bottom: 20px;
		margin-top: 20px;
	}

	#COD_UNIVEND_chosen > a {
		height: 56px;
		padding: 12px 16px;		
	}

	#COD_SEXOPES_chosen {
		font-size: 22px;
		margin-bottom: 20px;
		margin-top: 20px;
	}

	#COD_SEXOPES_chosen > a {
		height: 56px;
		padding: 12px 16px;		
	}

	.chosen-single{
		background-color: #ecf0f1!important;
		border: none!important;
	}

	.chosen-container-single .chosen-single abbr {
		top: 28px;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	.f15{
		font-size: 15px;
	}

	.mb-5{
		margin-bottom: 5px;
	}
</style>

<script src="libs/chosen.jquery.min.js"></script>
<link href="libs/chosen-bootstrap.css" rel="stylesheet" />

<form data-toggle="validator" role="form2" method="POST" id="formulario">
		
	<div class="container">

		<div class="push50"></div>

		<div class="row">

			<div class="col-xs-12">	
			
				<label class="text-danger" style="margin-bottom: -15px;">*</label>
				<input type="text" id="NOM_CLIENTE" name="NOM_CLIENTE" value="<?=$nom_cliente?>" class="form-control input-hg" placeholder="Nome" required/>
				<div class="errorNome" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div>

			</div>

			<div class="col-xs-12">

				<label class="text-danger" style="margin-bottom: -15px;">*</label>
				<input type="text" id="DAT_NASCIME" name="DAT_NASCIME" value="<?=$dat_nascime?>" class="form-control input-hg data" placeholder="Dt. Nascimento" required/>
				<div class="errorNascimen" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div>

			</div>

			<div class="col-xs-12">

				<label class="text-danger" style="margin-bottom: -15px;">*</label>
				<input type="text" id="DES_EMAILUS" name="DES_EMAILUS" value="<?=$des_emailus?>" class="form-control input-hg mb-5" placeholder="e-Mail" required/>
				<div class="errorEmail" style="color: red; font-size: 14px; display: none; margin-top: -5px; margin-bottom: 10px;">Campo obrigatório</div>

			</div>

			<div class="col-xs-12">
				<div class="push10"></div>
				<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" <?=$checkEmail?> class="switch" value="S">
				<label for="LOG_EMAIL" class="control-label f15">Desejo receber emails promocionais</label>
				<div class="push10"></div>
			</div>

			<div class="col-xs-12">

				<label class="text-danger" style="margin-bottom: -35px;">*</label>
				<select data-placeholder="Sexo" name="COD_SEXOPES" id="COD_SEXOPES" autocomplete="off" class="chosen-select-deselect" required>
					<option value=""></option>					
					<?php 
					
						$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by DES_SEXOPES";
						$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
					
						while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {														
							echo "<option value='".$qrLayout['COD_SEXOPES']."'>".$qrLayout['DES_SEXOPES']."</option>"; 
						}											
					?>	 							
				</select>
				<script type="text/javascript">$("#COD_SEXOPES").val("<?=$cod_sexopes?>").trigger("chosen:updated");</script>
				<div class="errorSexo" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div>

			</div>

			<div class="col-xs-12">

				<label style="margin-bottom: -15px;">&nbsp;</label>
				<input type="text" id="NUM_CELULAR" name="NUM_CELULAR" value="<?=$num_celular?>" class="form-control input-hg mb-5 text-center sp_celphones" placeholder="Tel. Celular" />
				<!-- <div class="errorCeluar" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div> -->

			</div>

			<div class="col-xs-12">
				<div class="push10"></div>
				<input type="checkbox" name="LOG_SMS" id="LOG_SMS" <?=$checkSms?> class="switch" value="S">
				<label for="LOG_SMS" class="control-label f15">Desejo receber SMS com promoções</label>
				<div class="push10"></div>
			</div>

			<div class="col-xs-12">

				<p style="margin-bottom: 0px;">Unidade mais próxima <span class="text-danger">*</span></p>
				<select data-placeholder="Unidade Mais Próxima" name="COD_UNIVEND" id="COD_UNIVEND" autocomplete="off" class="chosen-select-deselect" required>
					<option value=""></option>					
					<?php 
					
						$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
						$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
						while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
						 {																			
							echo"
								  <option value='".$qrListaUnive['COD_UNIVEND']."'>".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
								"; 
						}											
					?>	 							
				</select>
				<script type="text/javascript">$("#COD_UNIVEND").val("<?=$cod_univend?>").trigger("chosen:updated");</script>
				<div class="errorUnivend" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div>
				<div class="push20"></div>
			</div>

			


				<?php

					if($cod_empresa == 124){

				?>

					<div class="col-xs-12">
						<label class="text-danger" style="margin-bottom: -15px;">*</label>
						<input type="number" maxlength="4" id="DES_SENHAUS" name="DES_SENHAUS" class="form-control input-hg" style="-webkit-text-security: disc;" oninput="checkNumberFieldLength(this);" placeholder="Senha (4 dígitos numéricos)" required/>
					</div>

					<div class="col-xs-12">
						<label class="text-danger" style="margin-bottom: -15px;">*</label>
						<input type="number" maxlength="4" id="DES_SENHAUS_CONF" name="DES_SENHAUS_CONF" class="form-control input-hg" style="-webkit-text-security: disc;" oninput="checkNumberFieldLength(this);" placeholder="Confirme a senha" required/>
						<div class="errorLogin" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">*As senhas são diferentes/Campo senha vazio.</div>
					</div>

				<?php 

					}else{

				?>

				<div class="col-xs-12">
					<label class="text-danger" style="margin-bottom: -15px;">*</label>
					<input type="password" id="DES_SENHAUS" name="DES_SENHAUS" class="form-control input-hg" placeholder="Senha" />
				</div>

				<div class="col-xs-12">
					<label class="text-danger" style="margin-bottom: -15px;">*</label>
					<input type="password" id="DES_SENHAUS_CONF" name="DES_SENHAUS_CONF" class="form-control input-hg" placeholder="Confirme a senha" />
					<div class="errorLogin" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">*As senhas são diferentes/Campo senha vazio.</div>
				</div>

				<?php

					}

					if($txtBtn == "Cadastrar" && $log_termos == 'S'){

				?>
					<div class="col-xs-12">

						<input type="checkbox" name="LOG_TERMOS" id="LOG_TERMOS" class="switch" value="S" required>
						<label for="LOG_TERMOS" class="control-label f15">Aceito os Termos e Condições</label><label class="text-danger" style="margin-bottom: -15px;">*</label>
						<div class="errorTermos" style="color: red; font-size: 14px; display: none; margin-top: -13px; margin-bottom: 10px;">*É necessário aceitar os Termos e Condições.</div>

					</div>

				<?php 
					} 
				?>

				<div class="col-xs-12">
					<label style="margin-bottom: -15px;">&nbsp;</label>
		        	<button type="button" class="btn btn-primary btn-hg btn-block" name="btnCad" id="btnCad"><?=$txtBtn?></button>
		        </div>

		</div>
		
		<!--
		<div class="row">		 	
			
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<input type="text" class="form-control text-center data" name="c6" id="c6" value="<?php echo $buscaconsumidor['datanascimento'];?>" autocomplete="off" placeholder="Data de Nascimento" required>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>
		-->
		
		
		<div class="push50"></div>	
	</div><!-- /container -->
		
	<input type="hidden" name="KEY" id="KEY" value="<?=fnEncode($_SESSION["EMPRESA_COD"])?>">
	<input type="hidden" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?=$num_cgcecpf?>">
	<!-- <input type="hidden" name="opcao" id="opcao" value=""> -->
	<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
	<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

</form>


<?php include 'footer.php'; ?>

<script>

	$(document).ready(function(){
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$("#COD_SEXOPES").chosen();		
		$("#COD_UNIVEND").chosen();
		$('.cpf').mask('000.000.000-00', {reverse: true});
		$('.data').mask('00/00/0000');

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};

		$('.sp_celphones').on('input propertychange paste', function (e) {
		    var reg = /^0+/gi;
		    if (this.value.match(reg)) {
		        this.value = this.value.replace(reg, '');
		    }
		});

		$("#DES_EMAILUS").keypress(function(e) {
	      if(e.which === 32) 
	        return false;
	    });
		
		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$('#btnCad').click(function() {

			// alert('chega');

			var nome = $('#NOM_CLIENTE').val(),
			cpf = $('#NUM_CGCECPF').val(),
			nasc = $('#DAT_NASCIME').val(),
			email = $('#DES_EMAILUS').val(),
			sexo = $('#COD_SEXOPES').val(),
			univend = $('#COD_UNIVEND').val(),
			senha = $('#DES_SENHAUS').val(),
			con_senha = $('#DES_SENHAUS_CONF').val(),
			cod_cliente = "<?=$cod_cliente?>",
			log_termos = "<?=$log_termos?>",
			aceito = "S";

			if(cod_cliente != 0){
				opcao = "atualizar";
			}else{
				opcao = "cadastrar";
			}

			// alert(opcao);

			if(log_termos == 'S' && opcao == "cadastrar"){
				if(!$('#LOG_TERMOS').prop('checked')){
					aceito = "N";
					$('.errorTermos').show();
				}else{
					$('.errorTermos').hide();
				}
			}

			// alert(senha);
			// alert(con_senha);
			// alert(nome);
			// alert(nasc);
			// alert(email);
			// alert(sexo);
			// alert(univend);
			// alert(cpf);

			if(senha == con_senha && nome != "" && nasc != "" && email != "" && sexo != "" && univend != "" && senha != "" && cpf != ""){

				if(aceito == "S"){

					// alert(opcao);

					$.ajax({
						type: "POST",
						url: "ajxValidaCadastro.do?id=<?=fnEncode($cod_empresa)?>&opcao="+opcao,
						data: $("#formulario").serialize(),
						beforeSend:function(){
							$('#loadStep').html('<div class="loading"></div>');
						},				
						success:function(data){
							console.log(data);

							if(data.trim() == "1"){
								alert('Cadastro realizado com sucesso!');
								window.location.replace("app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
							}
							else if(data.trim() == "3"){
								alert('Cadastro atualizado com sucesso!');
								window.location.replace("app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
							}
							else if(data.trim() == "2"){
								alert('Cliente já é cadastrado.');
								window.location.replace("app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
							}
							else{
								alert("Erro ao cadastrar. Contate o suporte.");
								console.log(data);
							}
						},
						error: function(){
							$('#loadStep').html('Oops... Ocorreu um erro. Contate o suporte.');
						}
					});
				}

			}

			if(senha != con_senha || senha == ""){
				$('.errorLogin').show();
			}else{
				$('.errorLogin').hide();
			}

			if(cpf == ""){
				$('.errorCpf').show();
			}else{
				$('.errorCpf').hide();
			}

			if(nome == ""){
				$('.errorNome').show();
			}else{
				$('.errorNome').hide();
			}

			if(nasc == ""){
				$('.errorNascimen').show();
			}else{
				$('.errorNascimen').hide();
			}

			if(email == ""){
				$('.errorEmail').show();
			}else{
				$('.errorEmail').hide();
			}

			if(univend == ""){
				$('.errorUnivend').show();
			}else{
				$('.errorUnivend').hide();
			}

			if(sexo == ""){
				$('.errorSexo').show();
			}else{
				$('.errorSexo').hide();
			}
				
		});

	});

	// $('#formulario').validator().on('submit', function (e) {
	//   if (!e.isDefaultPrevented()) {
	//   	e.preventDefault();
	//     $.ajax({
	// 		method: 'POST',
	// 		url: 'ajxValidaCadastro.php',
	// 		data: $('#formulario').serialize(),
	// 		success:function(data){
	// 			console.log(data);

	// 			if(data.trim() == "Registro inserido!"){
	// 				alert('Cadastro realizado com sucesso!');
	// 				window.location.replace("http://adm.bunker.mk/app/app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
	// 			}
	// 			else if(data.trim() == "Cadastro Atualizado !"){
	// 				alert('Cadastro atualizado com sucesso!');
	// 				window.location.replace("http://adm.bunker.mk/app/app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
	// 			}
	// 			else{
	// 				alert(data);
	// 			}
	// 		},
	// 		error:function(){
				
	// 		}
	// 	});

	//   }
	// });

</script>


	