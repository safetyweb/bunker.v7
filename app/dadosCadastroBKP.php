<?php
include 'header.php'; 
$tituloPagina = "Cadastro";
include "navegacao.php"; 
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';
include_once '../totem/funWS/saldo.php';
// echo fnDebug('true');
//$parametros = fnDecode($_GET['key']);
$arrayCampos = explode(";", $_SESSION["KEY"]);

$dadoslogin = array(
	'0'=>$arrayCampos[0],
	'1'=>$arrayCampos[1],
	'2'=>$arrayCampos[3],
	'3'=>'maquina',
	'4'=>$arrayCampos[2]
);


$hashLocal = mt_rand();	

if( $_SERVER['REQUEST_METHOD']=='POST' )
{

	$cpf = fnLimpaDoc($_REQUEST['c1']);

	if(strlen($cpf)=='11')
	{    
	    $buscaconsumidor = fnconsulta($cpf, $dadoslogin);
	    
	 
	}else{
	    $buscaconsumidor = fnconsultacnpf($cpf, $dadoslogin); 
	    
	}


	               
	if($buscaconsumidor['localizacaocliente']=='13')
	    {
	      $cpf= $cpf; 
	    }else{
        if($buscaconsumidor['cpf']=='00000000000')
        {   
        $cpf=$cpf;  
        }else
        {
          $cpf=$buscaconsumidor['cpf'];
        }    
	} 

	$request = md5( implode( $_POST ) );
	
	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;
		
		// $cpf = fnLimpaDoc($_REQUEST['cpf']);
		//$cod_orcamento = fnLimpacampo($_REQUEST['COD_ORCAMENTO']);
                                            
		// $opcao = $_REQUEST['opcao'];
		// $hHabilitado = $_REQUEST['hHabilitado'];
		// $hashForm = $_REQUEST['hashForm'];                
                      
                       
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
	
?>

<form data-toggle="validator" role="form2" method="POST" id="formulario">
		
	<div class="container">

		<div class="push50"></div>

		<div class="row">	
			
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
	                <input type="text" class="form-control" name="c2" id="c2" value="<?php echo $buscaconsumidor['nome'];?>" autocomplete="off" placeholder="Nome" required>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row">		 	
			
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<input type="text" class="form-control text-center data" name="c6" id="c6" value="<?php echo $buscaconsumidor['datanascimento'];?>" autocomplete="off" placeholder="Data de Nascimento" required>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row">
				
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<select name="c7" id="c7" class="form-control" required>
						<option value=""></option>					
						<?php 
						
							$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by DES_SEXOPES";
							$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
						
							while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {														
								echo "<option value='".$qrLayout['COD_SEXOPES']."'>".$qrLayout['DES_SEXOPES']."</option>"; 
							}											
						?> 							
					</select>
					<script>$("#formulario #c7").val("<?php echo $buscaconsumidor['sexo']; ?>").trigger("chosen:updated"); </script>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row">				
				
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<input type="text" class="form-control" name="c3" id="c3" value="<?php echo $buscaconsumidor['email'];?>" autocomplete="off" placeholder="e-Mail">
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row">				
				
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<input type="password" class="form-control" name="c5" id="c5" value="" autocomplete="off" placeholder="Senha" required>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row"> 
				
			<div class="col-xs-10 col-xs-offset-1">
				<div class="form-group">
					<input type="text" class="form-control text-center sp_celphones" minlength=15 name="c4" id="c4" maxlength="14" value="<?php echo $buscaconsumidor['telresidencial'];?>" autocomplete="off" placeholder="Telefone Celular" required>
					<div class="help-block with-errors"></div>
				</div>
			</div>

		</div>

		<div class="row">
				 	
			<div class="col-xs-10 col-xs-offset-1">
				<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp; Atualizar Cadastro</button>
			</div>

		</div>		
			
	</div><!-- /container -->
		
	<input type="hidden" name="KEY" id="KEY" value="<?=fnEncode($_SESSION["KEY"])?>">
	<input type="hidden" name="CPF" id="CPF" value="<?=$cpf?>">
	<input type="hidden" name="opcao" id="opcao" value="">
	<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
	<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

</form>


<?php include 'footer.php'; ?>

<script>

	$(document).ready(function(){
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};
		
		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$("#c3").keypress(function(e) {
	      if(e.which === 32) 
	        return false;
	    });

	});

	$('#formulario').validator().on('submit', function (e) {
	  if (!e.isDefaultPrevented()) {
	  	e.preventDefault();
	    $.ajax({
			method: 'POST',
			url: 'ajxValidaCadastro.php',
			data: $('#formulario').serialize(),
			success:function(data){
				console.log(data);

				if(data.trim() == "Registro inserido!"){
					alert('Cadastro realizado com sucesso!');
					window.location.replace("http://adm.bunker.mk/app/app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
				}
				else if(data.trim() == "Cadastro Atualizado !"){
					alert('Cadastro atualizado com sucesso!');
					window.location.replace("http://adm.bunker.mk/app/app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
				}
				else{
					alert(data);
				}
			},
			error:function(){
				
			}
		});

	  }
	});

</script>


	