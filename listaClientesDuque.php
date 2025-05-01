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
			
			$cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
			$cod_tpentid = fnLimpaCampoZero($_REQUEST['COD_TPENTID']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$nom_entidad = fnLimpaCampo($_REQUEST['NOM_ENTIDAD']);
			$num_cgcecpf = fnLimpaCampoZero(LIMPA_DOC($_REQUEST['NUM_CGCECPF']));
			$des_enderc = fnLimpaCampo($_REQUEST['DES_ENDERC']);
			$num_enderec = fnLimpaCampo($_REQUEST['NUM_ENDEREC']);
			$des_bairroc = fnLimpaCampo($_REQUEST['DES_BAIRROC']);
			$num_cepozof = fnLimpaCampo(LIMPA_DOC($_REQUEST['NUM_CEPOZOF']));
			$nom_cidades = fnLimpaCampo($_REQUEST['NOM_CIDADES']);
			$nom_estados = fnLimpaCampo($_REQUEST['NOM_ESTADOS']);
			$num_telefone = fnLimpaCampo(LIMPA_DOC($_REQUEST['NUM_TELEFONE']));
			$num_celular = fnLimpaCampo(LIMPA_DOC($_REQUEST['NUM_CELULAR']));
			$email = fnLimpaCampo($_REQUEST['EMAIL']);
			$nom_respon = fnLimpaCampo($_REQUEST['NOM_RESPON']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_ENTIDADE (
				 '".$cod_entidad."', 
				 '".$cod_tpentid."',
				 '".$cod_empresa."', 
				 '".$nom_entidad."', 
				 '".$num_cgcecpf."', 
				 '".$des_enderc."', 
				 '".$num_enderec."', 
				 '".$des_bairroc."',
				 '".$num_cepozof."',
				 '".$nom_cidades."',
				 '".$nom_estados."',
				 '".$num_telefone."',
				 '".$num_celular."',
				 '".$email."',
				 '".$nom_respon."',
				 '".$cod_usucada."',
				 '".$opcao."'    
			        );";
					
                mysqli_query(connTemp($cod_empresa,''),$sql);				
				
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
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

?>
	
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>									
								
									<?php 
									//menu superior - empresas
									$abaFormalizacao = 1075;
									$abaEmpresa = 1183;	
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 14: //rede duque
											include "abasEmpresaDuque.php";
											break;
										default;											
											include "abasFormalizacaoEmp.php";
											break;
									}									
									?>
									
									<div class="push50"></div> 			

										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Nome do Cliene</th>
													  <th class="bg-primary">Código Externo</th>
													</tr>
												  </thead>
												<tbody>
												
												<?php 
													$sql = "select ENTIDADE.COD_ENTIDAD,"
																 ."ENTIDADE.COD_TPENTID,"
																 ."ENTIDADE.COD_EXTERNO,"
																 ."ENTIDADE.COD_EMPRESA,"
																 ."ENTIDADE.NOM_ENTIDAD,"
																 ."ENTIDADE.NUM_CGCECPF,"
																 ."ENTIDADE.DES_ENDERC,"
																 ."ENTIDADE.NUM_ENDEREC,"
																 ."ENTIDADE.DES_BAIRROC,"
																 ."ENTIDADE.NUM_CEPOZOF,"
																 ."ENTIDADE.NOM_CIDADES,"
																 ."ENTIDADE.NOM_ESTADOS,"
																 ."ENTIDADE.NUM_TELEFONE,"
																 ."ENTIDADE.NUM_CELULAR,"
																 ."ENTIDADE.EMAIL,"
																 ."ENTIDADE.NOM_RESPON,"
																 ."TIPOENTIDADE.DES_TPENTID,"
																 ."EMPRESAS.NOM_EMPRESA "
															."from ENTIDADE " 
																."left join $connAdm->DB.empresas ON ENTIDADE.COD_EMPRESA = $connAdm->DB.empresas.COD_EMPRESA "
																."left join $connAdm->DB.tipoentidade ON entidade.COD_TPENTID = $connAdm->DB.tipoentidade.COD_TPENTID "
															."where $connAdm->DB.empresas.COD_EMPRESA = $cod_empresa "
															."order by NOM_ENTIDAD";
													
													//fntesteSql(connTemp($cod_empresa,''),$sql);	  
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;	
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_ENTIDAD']."</td>
															  <td>".$qrBuscaModulos['NOM_ENTIDAD']."</td>
															  <td>".$qrBuscaModulos['COD_EXTERNO']."</td>
															</tr>
															
															<input type='hidden' id='ret_COD_ENTIDAD_".$count."' value='".fnEncode($qrBuscaModulos['COD_ENTIDAD'])."'>
															<input type='hidden' id='ret_NOM_ENTIDAD_".$count."' value='".$qrBuscaModulos['NOM_ENTIDAD']."'>
															<input type='hidden' id='ret_COD_EXTERNO_".$count."' value='".$qrBuscaModulos['COD_EXTERNO']."'>
															"; 
														  }											
												?>

												</tbody>
												</table>
												
												<input type="hidden" name="codBusca" id="codBusca" value="">
												
												</form>

											</div>
											
										</div>										
									
									<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
					
	<?php
	if (!is_null($RedirectPg)) {
		$DestinoPg = fnEncode($RedirectPg);		
	}else {
		$DestinoPg = "";		
		}	
	?>				
	
	<script type="text/javascript">
	
		function retornaForm(index){
			
			$("#codBusca").val($("#ret_COD_ENTIDAD_"+index).val());			
			//$("#codBusca").val($("#ret_IDC_"+index).val());			
			//$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
			$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC='+$("#ret_COD_ENTIDAD_"+index).val());					
			$('#formLista').submit();					
		}
	
	</script>
		
	</script>	