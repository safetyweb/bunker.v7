<?php
include './_system/_functionsMain.php';

$opcao = fnLimpaCampo($_GET['opcao']);
$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
$campo = $_REQUEST['CAMPO'];

switch($opcao){

	case 'CPF':

		$cpf = fnLimpaCampo(fnLimpaDoc($campo));

		$sql = "SELECT DAT_NASCIME, COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = '$cpf' AND COD_EMPRESA = $cod_empresa";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		if(mysqli_num_rows($arrayQuery) == 1 && $cpf != "00000000000"){

			$qrCliente = mysqli_fetch_assoc($arrayQuery);
			$anoNascimento = substr($qrCliente['DAT_NASCIME'], 6, 4);
			// fnEscreve($qrCliente['COD_CLIENTE']);
?>

            <div class="push20"></div>

            <div style="height: 73px;">
            	<div class="alert alert-warning" role="alert" style="display: none;" id="alertAno">
	            	ANO INFORMADO DIFERENTE DO ANO CADASTRADO.
	            </div>
            </div>

	        <div class="row text-center">
	          <div class="col-xs-12">
	            <h4 class="text-white" style="font-weight: 900;">INFORME CORRETAMENTE O ANO DE NASCIMENTO:</h4>
	          </div>
	        </div>

            <div class="push30"></div> 

            <form class="form-signin" method="post" action="reenvioSenha.php">
                <label for="DES_ANO" class="sr-only">ANO</label>
                <input type="text" name="DES_ANO" id="DES_ANO" class="form-control int text-center" placeholder="EX: 1990" maxlength="4" required="" autofocus="">
                <input type="hidden" name="CLIENTE" id="CLIENTE" value="<?=fnEncode($qrCliente[COD_CLIENTE])?>">
                <input type="hidden" name="CPF" id="CPF" value="<?=$campo?>">
                <div class="push50"></div>
                <a href="javascript:void(0)" class="btn btn-default btn-block" onclick='verificaAno($("#DES_ANO").val())'>CONTINUAR</a>
            </form>

            <script>
            	function verificaAno(ano){
            		var anoCliente = "<?=$anoNascimento?>";
            		if(anoCliente == ano){
            			trocaSenha("CLIENTE");
            		}else{
            			$("#alertAno").fadeIn(100);
            		}
            	}
            </script>

<?php
		}else{
?>

			<div class="push20"></div>

            <div class="alert alert-warning" role="alert">
            CPF NÃO ENCONTRADO
            </div>          

	        <div class="row text-center">
	          <div class="col-xs-12">
	            <h4 class="text-white" style="font-weight: 900;">INFORME CORRETAMENTE O CPF DO CADASTRO</h4>
	          </div>
	        </div>

            <div class="push30"></div> 

            <form class="form-signin">
	            <label for="cpf" class="sr-only">CPF</label>
	            <input type="text" name="CPF" id="CPF" class="form-control cpfcnpj text-center" placeholder="Seu CPF" maxlength="14" required="" autofocus="">
	            <!-- <div class="push10"></div>
	            <label for="email" class="sr-only">Email</label>
	            <input type="email" name="email" id="email" class="form-control text-center" placeholder="e-Mail" required=""> -->
	            <div class="push50"></div>
	            <!-- <button class="btn btn-default btn-block" type="submit" id="enviar">BUSCAR</button> -->
	            <a href="javascript:void(0)" class="btn btn-default btn-block" data-type="CPF" onclick='trocaSenha($(this).attr("data-type"))'>CONTINUAR</a>
            </form>

<?php
		}

	break;

	case 'CLIENTE':

		$cod_cliente = $campo;
?>

			<div class="push20"></div>

	        <div class="row text-center">
	          <div class="col-xs-12">
	            <h4 class="text-white" style="font-weight: 900;">CADASTRO VALIDADO. <br>CRIE SUA SENHA:</h4>
	          </div>
	        </div>

            <div class="push30"></div> 

            <form data-toggle="validator" role="form2" method="post" id="formulario" action="reenvioSenha.php">
                <label for="DES_ANO" class="sr-only">ANO</label>
                <div class="form-group">
	              <input type="password" id="DES_SENHAUS" name="DES_SENHAUS" class="form-control input-hg text-center" placeholder="Insira sua senha" data-minlength="6" data-minlength-error="Senha muito curta" maxlength="6" autocomplete="new-password" required>
	              <span toggle="#DES_SENHAUS" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	              <div class="help-block with-errors"><b>Senha de 6 dígitos</b></div>
	            </div>
				<div class="form-group">
	              <input type="password" id="DES_SENHAUS_CONF" name="DES_SENHAUS_CONF" class="form-control input-hg text-center" placeholder="Confirme a senha" maxlength="6" data-match="#DES_SENHAUS" data-match-error="Senhas diferentes" required>
	              <div class="help-block with-errors"></div>
	            </div>
                <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
                <div class="push20"></div>
                <div class="push5"></div>
                <button type="submit" class="btn btn-default btn-block">ALTERAR</button>
            </form>

            <script>

            	$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
    			$('#formulario').validator();

            	$(".toggle-password").click(function() {

			      $(this).toggleClass("fa-eye fa-eye-slash");
			      var input = $($(this).attr("toggle"));
			      if (input.attr("type") == "password") {
			        input.attr("type", "text");
			      } else {
			        input.attr("type", "password");
			      }

			    });

            </script>

<?php

	break;

}

?>