<?php
 
$hashLocal = mt_rand();	

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
	$request = md5( implode( $_POST ) );
	
	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;
		
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_justifica = fnLimpaCampo($_REQUEST['DES_JUSTIFICA']);
		$chave_linha = fnLimpaCampo($_REQUEST['CHAVE_LINHA']);
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

                  
		if ($opcao != ''){

			//mensagem de retorno
			$sql = "INSERT INTO justificativa(
                                    COD_EMPRESA,
                                    DES_JUSTIFICA,
									COD_USUCADA
								) VALUES(
									$cod_empresa,
									'$des_justifica',
									$cod_usucada
								)";
			// echo($sql."_");
            mysqli_query(connTemp($cod_empresa,''),$sql);

?>
			<script type="text/javascript">
				parent.location.reload();
				parent.$('#popModal').modal('toggle');
			</script>
<?php 

			$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";			
			$msgTipo = 'alert-success';
		}                
	}
}
	
//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
        
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	$chave_linha = fnDecode($_GET['idc']);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaEmpresa)){
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
											
}else {	
	$nom_empresa = "";
}

if ($popUp != "true"){  

?>							
	<div class="push30"></div> 
<?php 
} 
?>

	<div class="row">				

		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
			<div class="portlet" style="padding: 0 20px 20px 20px;" >
			<?php } ?>
			
				<?php if ($popUp != "true"){  ?>
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?>
					</div>
					<?php include "atalhosPortlet.php"; ?>
				</div>
				<?php } ?>								
				
				<div class="portlet-body">
					
					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>	
												
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend> 

							<div class="row">
								<div class="col-md-4 col-md-offset-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Justificativa</label>
										<input type="text" class="form-control input-sm" name="DES_JUSTIFICA" id="DES_JUSTIFICA" maxlength="60" value="">
									</div>
								</div>
							</div>           

						</fieldset>


						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">

							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->

							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
							
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

						<div class="push5"></div> 

					</form>
					
					<div class="push50"></div>									
				
					<div class="push"></div>
				
				</div>

			</div>

		</div>

	</div>								
					
	<div class="push50"></div>