<?php 
	include 'header.php'; 
	$tituloPagina = "Cadastro";
	include "navegacao.php"; 
?>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="cadastro.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>">
	
    <div class="container">

		<div class="push50"></div>
		<div class="row">		
			
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<label for="inputName" class="control-label required"></label>
					<input type="text" class="form-control input-lg text-center cpfcnpj" name="c1" id="c1" value="" placeholder="Informe seu CPF/CNPJ" required>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row">				
			
			<div class="col-xs-10 col-xs-offset-1">
				<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
			</div>
					
			
		</div><!-- /row -->
        

    </div> <!-- /container -->

</form>

<?php include 'footer.php'; ?>

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
	
</script>