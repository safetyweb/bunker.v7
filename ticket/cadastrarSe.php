<?php

include "../_system/_functionsMain.php";
$cod_empresa = fnLimpacampo($_GET['codEmpresa']);

$sql = "SELECT COD_DOMINIO, DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ";
// echo($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
$cod_dominio = $qrBuscaSiteExtrato['COD_DOMINIO'];

if($cod_dominio == 2){
	$extensaoDominio = ".fidelidade.mk";
}else{
	$extensaoDominio = ".mais.cash";
}

//busaca clientes por cpf

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//echo fnDebug('true');
?>

<link href="css/main.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<style>

body {
	background-color: transparent;
}

</style>

<div class="container" id="containerCadastrar">
    <form id='cadastrar-se'>
		<h6 style="margin-bottom: 15px;">Informe seu CPF/CNPJ<h6>
		<input type="text" id="cpf" name="cpf" class="form-control input-hg cpfcnpj" placeholder="CPF/CNPJ" />
        <button type="button" class="btn btn-primary btn-hg btn-block" name="btnBuscarCpf" id="btnBuscarCpf">Buscar</button>
		<div class="push10"></div>
		<div class="errorLogin" style="color: red; text-align: center; display: none; margin-top: 15px;">CPF/Cartão não encontrado</div>
		<div id="loadStep"></div>
	</form>
	
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.mask.min.js"></script>

<script type="text/javascript">

	$(document).ready( function() {			
		
		if($('.cpfcnpj').val() != undefined){
			mascaraCpfCnpj($('.cpfcnpj'));
		}
	

		$(document).on('keypress',function(e) {
		    if(e.which == 13) {
		    	e.preventDefault();
				e.stopPropagation();
		        $("#btnBuscarCpf").click();
		    }
		});

		$('#btnBuscarCpf').click(function() {
			
			var pCpf = $('#cpf').val().replace(/[^0-9]/g, ''),
				pTipo = "buscaCpf";

			if(pCpf != ''){

				if(pCpf.length == 14){
					pTipo = "buscaCnpj";
				}

				// alert(pCpf.length);
				// alert(pTipo);
			
				$.ajax({
					type: "GET",
					url: "ajxCadastrarSe.do",
					data: { cpf:pCpf, codEmpresa: "<?php echo $cod_empresa ?>", tipo: pTipo },
					beforeSend:function(){
						$('#loadStep').html('<div class="loading"></div>');
					},				
					success: function(msg) {
						var retorno = msg.trim().substring(0, 13).trim();
						if(retorno != 'sem_resultado'){
							$('#loadStep').hide();
							$('#containerCadastrar').html(msg);
							// alert(msg);
						}else{
							$('#loadStep').hide();
							$('.errorLogin').show();
						}
					}
				});

			}else{
				$('.errorLogin').show();
			}	
				
		});
		
	});

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


</script>