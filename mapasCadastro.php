<?php
if( $_SERVER['REQUEST_METHOD']=='POST' )
{
	if (is_array(@$_POST['DES_MAPA_TIPOS'])){
		$_POST['DES_MAPA_TIPOS'] = implode(",",array_keys($_POST['DES_MAPA_TIPOS']));
	}else{
		$_POST['DES_MAPA_TIPOS'] = "";
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
		

		$cod_mapa = fnLimpacampoZero($_REQUEST['COD_MAPA']);
		$nom_mapa = fnLimpacampo($_REQUEST['NOM_MAPA']);
		$log_pessoas = fnLimpacampo(@$_REQUEST['LOG_PESSOAS']);
		$log_unidades = fnLimpacampo(@$_REQUEST['LOG_UNIDADES']);
		$log_pessoas = ($log_pessoas == ""?"N":$log_pessoas);
		$log_unidades = ($log_unidades == ""?"N":$log_unidades);
		$des_mapa_tipos = fnLimpacampo(@$_POST['DES_MAPA_TIPOS']);
		$refresh = "S";

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
					
		if ($opcao != ''){

			$sql = "CALL SP_ALTERA_MAPAS (
			 '".$cod_mapa."', 
			 '".$cod_empresa."', 
			 '".$nom_mapa."', 
			 '".$log_pessoas."',
			 '".$log_unidades."',
			 '".$des_mapa_tipos."',
			 '".$opcao."'
			) ";
			
//				echo $sql;exit;
			
			mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());				
			
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
		$des_mapa_tipos = explode(",","0".$des_mapa_tipos);

		

	}
}

include("abasMapasConfig.php");
?>


<?php if ($msgRetorno <> '') { ?>	
<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 <?php echo $msgRetorno; ?>
</div>
<?php } ?>	

<script>
window.parent.$("#REFRESH").val("<?=$refresh?>");
</script>

<div class="login-form">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
								
	<fieldset>
		<legend>Dados Gerais</legend> 
		
			<div class="row">

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label required">Código</label>
						<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_MAPA" id="COD_MAPA" value="<?=@$cod_mapa?>">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="inputName" class="control-label required">Empresa</label>
						<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
					</div>														
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<label for="inputName" class="control-label required">Nome</label>
						<input type="text" class="form-control input-sm" name="NOM_MAPA" id="NOM_MAPA" maxlength="50" data-error="Campo obrigatório" required value="<?=@$nom_mapa?>">
						<div class="help-block with-errors"></div>
					</div>
				</div>

				
			</div>
			<div class="row">

				<div class="col-md-3">   
					<div class="form-group">
						<label for="inputName" class="control-label">Mostra Pessoas</label> 
						<div class="push5"></div>
							<label class="switch">
							<input type="checkbox" name="LOG_PESSOAS" id="LOG_PESSOAS" class="switch" value="S" <?=(@$log_pessoas == "S"?"checked":"")?>>
							<span></span>
							</label>
					</div>
				</div>
				
				<div class="col-md-3">   
					<div class="form-group">
						<label for="inputName" class="control-label">Mostra Unidades</label> 
						<div class="push5"></div>
							<label class="switch">
							<input type="checkbox" name="LOG_UNIDADES" id="LOG_UNIDADES" class="switch" value="S"  <?=(@$log_unidades == "S"?"checked":"")?>>
							<span></span>
							</label>
					</div>
				</div>

				<?php
				$sql = "SELECT * FROM mapas_tipos
							WHERE COD_EMPRESA = $cod_empresa AND LOG_CONFIRM='S'
							ORDER BY NOM_MAPA_TIPO
						  ";
				$result = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
				while($qr = mysqli_fetch_assoc($result)){
					?>
					<div class="col-md-3">   
						<div class="form-group">
							<label for="inputName" class="control-label">Mostra <?=$qr["NOM_MAPA_TIPO"]?></label> 
							<div class="push5"></div>
								<label class="switch">
								<input type="checkbox" name="DES_MAPA_TIPOS[<?=$qr["COD_MAPA_TIPO"]?>]" class="switch" value="S"  <?=(in_array($qr["COD_MAPA_TIPO"], $des_mapa_tipos)?"checked":"")?>>
								<span></span>
								</label>
						</div>
					</div>
				<?php
				}
				?>

			</div>
			
	</fieldset>

	<input type="hidden" name="opcao" id="opcao" value="">
	<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
	<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">	
	
	<div class="push10"></div>
	<hr>	
	<div class="form-group text-right col-lg-12">
		
		<?php if ($tipo == "CAD"){ ?>
			<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
		<?php }else{ ?>
			<script>
			$(document).ready(function(){
				$('#formulario').validator('validate');
				$("#formulario #hHabilitado").val('S');
				RefresListaEnderecos("<?=$cod_empresa?>","<?=$cod_mapa?>");
			});
			</script>
			
			<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
			<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
		<?php } ?>
		
	</div>
	
	
	
	<div class="push5"></div> 
	
	</form>


	<div class="push"></div>

</div>								

<!-- fim Portlet -->