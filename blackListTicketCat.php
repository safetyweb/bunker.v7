<?php
	
	//echo fnDebug('true');

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

			$cod_blklist = fnLimpaCampoZero($_REQUEST['COD_BLKLIST']);
			$tip_blklist = fnLimpaCampo($_REQUEST['TIP_BLKLIST']);
			$nom_blklist = fnLimpaCampo($_REQUEST['NOM_BLKLIST']);
			$abv_blklist = fnLimpaCampo($_REQUEST['ABV_BLKLIST']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
						
			//array categorias escolhidas 
			if (isset($_POST['COD_CATEGOR'])){
				$Arr_COD_CATEGOR = $_POST['COD_CATEGOR'];
				//print_r($Arr_COD_CATEGOR);			 
			 
			   for ($i=0;$i<count($Arr_COD_CATEGOR);$i++) 
			   { 
				$cod_categor = $cod_categor.$Arr_COD_CATEGOR[$i].",";
			   } 
			   
			   $cod_categor = substr($cod_categor,0,-1);
				
			}else{$cod_categor = "0";}

			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			//fnEscreve($des_icones);	
			
			if ($opcao != ''){
			
				$sql = "update BLACKLISTTKT set COD_CATEGOR = '$cod_categor' where COD_BLKLIST = $cod_blklist ";
				
				//echo $sql;
				
				mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());				
				
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
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_blklist = fnDecode($_GET['idB']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = $cod_empresa ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];

		}

		//busca dados da blacklist
		$sql = "SELECT * FROM blacklisttkt where COD_BLKLIST = $cod_blklist and COD_EXCLUSA = 0 ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrBuscaBlackList = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaBlackList)){
			$nom_blklist = $qrBuscaBlackList['NOM_BLKLIST'];
			$cod_categorArray = explode(",", $qrBuscaBlackList['COD_CATEGOR']);
			//print_r($cod_categorArray);
		}

		
	}else {
		$cod_empresa = 0;		
		$nom_empresa = "";
	
	}
	
	//fnMostraForm();
	//fnEscreve($cod_categorArray);

?>
			
	<div class="push30"></div> 
	
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

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
							
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BLKLIST" id="COD_BLKLIST" value="<?php echo $cod_blklist; ?>">
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
											<label for="inputName" class="control-label required">Blacklist</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_BLKLIST" id="NOM_BLKLIST" value="<?php echo $nom_blklist; ?>">
										</div>														
									</div>									
									
								</div>
								
								<div class="push10"></div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Categorias dos produtos do hábito de consumo (para exclusão)</label>
												<select data-placeholder="Selecione a(s) categoria(s) desejada(s)" name="COD_CATEGOR[]" id="COD_CATEGOR" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
													<?php 
													
														$sql = "select * from CATEGORIA where COD_EMPRESA = ".$cod_empresa." order by DES_CATEGOR";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrListaCategorias = mysqli_fetch_assoc($arrayQuery))
														  {	
													  
															if(recursive_array_search($qrListaCategorias['COD_CATEGOR'],$cod_categorArray) !== false)
															{ $checado = "selected";
															}else{ $checado = "bosta"; }	
														
															echo"
																  <option value='".$qrListaCategorias['COD_CATEGOR']."' ".$checado.">".$qrListaCategorias['DES_CATEGOR']."</option> 
																"; 
															  }											

													?>
												</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>		
								
						</fieldset>	
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">							
						
							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<?php if ($cod_categorArray == 0) {?>	
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php } else { ?>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php } ?>	
							
						</div>
						
						
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						<div class="push5"></div> 
						
						</form>
						
						<div class="push50"></div>

					</div>								
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</div>

	<!-- modal -->									
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="push20"></div> 
	
	<script type="text/javascript">
		
        $(document).ready( function() {
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
	
			
        });			
		
	</script>	