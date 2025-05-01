<?php
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	$cod_externo = 0;
	$des_produto = "";
	$cod_orcamento = fnDecode($_GET['idO']);
	
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
			
			$cod_venda = 0;
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_produto = fnLimpacampoZero($_REQUEST['COD_PRODUTO']);
			$cod_categor = fnLimpacampoZero($_REQUEST['COD_CATEGOR']);
			$cod_subcate = fnLimpacampoZero($_REQUEST['COD_SUBCATE']);
			
			$atributo1 = fnLimpacampo($_REQUEST['ATRIBUTO1']);
			$atributo2 = fnLimpacampo($_REQUEST['ATRIBUTO2']);
			
			if($_REQUEST['opcao'] != "CAD"){
				$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
				$des_produto = fnLimpacampo($_REQUEST['DES_PRODUTO']);				
			}
			
			$qtd_produto = fnLimpacampo($_REQUEST['QTD_PRODUTO']);
			$val_unitario = fnLimpacampo($_REQUEST['VAL_UNITARIO']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
						
			if ($opcao != ''){
				
				if ($opcao == 'CAD'){
					
					$sql = "CALL SP_ALTERA_AUXVENDA (
					 '".$cod_venda."', 
					 '".$cod_orcamento."', 
					 '".$cod_produto."',
					 '".fnValorSql($qtd_produto)."', 
					 '".fnValorSql($val_unitario)."',
					 '".$opcao."'    
					) ";
					
					//echo $sql;				
					//fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());
				
				}	
				//echo $sql;
					
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
				?>		
						<script>
						try { parent.$('#REFRESH_PRODUTOS').val('S'); } catch(err) {}
						</script>
		
				<?php						
						break;
					case 'BUS':
						$msgRetorno = "Pesquisa realizada com <strong>sucesso!</strong>";		
						break;
					case 'ALT':
						$msgRetorno = "Pesquisa realizada com <strong>sucesso!</strong>";		
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
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);			
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
		
		$sql = "select  A.*,B.NOM_EMPRESA as  NOM_EMPRESA from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '".$cod_empresa."' ";		
		
		
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
 
		if (isset($arrayQuery)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
			
												
	}else {
		$cod_empresa = 0;		
		
	}      
	
	//fnMostraForm();
	//fnEscreve($des_produto);
	//fnEscreve($cod_categor);
	//fnEscreve($cod_subcate);
		
?>

	<?php if ($popUp != "true"){  ?>							
	<div class="push30"></div> 
	<?php } ?>
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
			<div class="portlet" style="padding: 0 20px 20px 20px;" >
			<?php } ?>
			
				<?php if ($popUp != "true"){  ?>
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
					
					<div class="push10"></div> 

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						
						<div class="push10"></div>	
							
						<div class="row">
						
							<div class="col-md-2"></div>
						
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label required">Tipo da Ocorrência </label>
										<select data-placeholder="Selecione o tipo de lançamento" name="COD_OCORREN" id="COD_OCORREN" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>					
											<?php 																	
												$sql = "SELECT * FROM OCORRENCIAMARKA WHERE LOG_OCORREN = 'A' order by DES_OCORREN ";
												$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
											
												while ($qrListaOcorrencia = mysqli_fetch_assoc($arrayQuery))
												  {														
													echo"
														  <option value='".$qrListaOcorrencia['COD_OCORREN']."'>".$qrListaOcorrencia['DES_OCORREN']."</option> 
														"; 
													  }											
											?>	
										</select>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Valor do Desconto</label>
									<input type="text" class="form-control input-sm text-right money" name="VAL_DESCONTO" id="VAL_DESCONTO" maxlength="50" data-error="Campo obrigatório">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-2"></div>
							
							<div class="push10"></div>	
							
							<div class="col-md-2"></div>
							
							<div class="col-md-8">
								<div class="form-group">
									<label for="inputName" class="control-label">Observação</label>
									<input type="text" class="form-control input-sm" name="OBSERVACAO" id="OBSERVACAO" data-error="Campo obrigatório">
									<div class="help-block with-errors"></div>
								</div>
							</div>	
							
							<div class="col-md-2"></div>
							
						</div>
					
															
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-left col-lg-12">
							<div class="pull-right">
								<button type="button" id="addDescontoModal" class="btn btn-info getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar desconto</button>
							</div>
						</div>
															
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$( "#addDescontoModal" ).click(function() {
			  parent.$('#VAL_DESCONTO').val($('#VAL_DESCONTO').val());
			  parent.$('#COD_OCORREN').val($('#COD_OCORREN').val());
			  parent.$('#OBSERVACAO').val($('#OBSERVACAO').val());
			  parent.$('#popModal').modal('hide');
			});
			
			
		});	
	</script>	