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

			$cod_termo = fnLimpaCampoZero($_REQUEST['COD_TERMO']);			
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_tipo = fnLimpaCampoZero($_REQUEST['COD_TIPO']);
			$nom_termo = fnLimpaCampo($_REQUEST['NOM_TERMO']);			
			$abv_termo = fnLimpaCampo($_REQUEST['ABV_TERMO']);
			$des_termo = addslashes(htmlentities($_REQUEST['DES_TERMO']));
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];	
			
			//fnEscreve($nom_submenus);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO TERMOS_EMPRESA(
												COD_EMPRESA,
												COD_TIPO,
												NOM_TERMO,
												ABV_TERMO,
												LOG_ATIVO,
												DES_TERMO
											) VALUES(
												'$cod_empresa',
												'$cod_tipo',
												'$nom_termo',
												'$abv_termo',
												'$log_ativo',
												'$des_termo'
											)";
						
						// fnEscreve($sql);				
						// fnTestesql(connTemp($cod_empresa, ''), $sql);
						mysqli_query(connTemp($cod_empresa, ''), $sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE TERMOS_EMPRESA SET
												COD_TIPO = '$cod_tipo',
												NOM_TERMO = '$nom_termo',
												ABV_TERMO = '$abv_termo',
												LOG_ATIVO = '$log_ativo',
												DES_TERMO = '$des_termo',
												COD_ALTERAC = '$cod_usucada'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_TERMO = $cod_termo";
						
						// fnEscreve($sql);				
						// fnTestesql(connTemp($cod_empresa, ''), $sql);
						mysqli_query(connTemp($cod_empresa, ''), $sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';

				?>
				<script>parent.$('#REFRESH_TERMO').val('S');</script>
				<?php 
				
			}  
			

		}
	}


	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
	} else {
		$cod_empresa = 0;
		//fnEscreve('entrou else');
	}

	// fnEscreve(fnDecode($_GET['idt']));

	if (is_numeric(fnLimpacampo(fnDecode($_GET['idt'])))) {

		$cod_termo = fnLimpaCampoZero(fnDecode($_GET['idt']));

		$sqlTermo = "SELECT * FROM TERMOS_EMPRESA 
					 WHERE COD_EMPRESA = $cod_empresa 
					 AND COD_TERMO = $cod_termo";

		$arrayTermo = mysqli_query(connTemp($cod_empresa,''), $sqlTermo);
		$qrTermo = mysqli_fetch_assoc($arrayTermo);

		$cod_tipo = $qrTermo['COD_TIPO'];
		$nom_termo = $qrTermo['NOM_TERMO'];			
		$abv_termo = $qrTermo['ABV_TERMO'];
		$des_termo = $qrTermo['DES_TERMO'];
		if ($qrTermo['LOG_ATIVO'] == 'S') {$checkAtivo='checked';}else{$checkAtivo='';}

	}else{

		$cod_termo = 0;
		$cod_tipo = 0;
		$nom_termo = '';			
		$abv_termo = '';
		$des_termo = '';

	}

	//fnMostraForm();

	// fnEscreve($des_termo);

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
										<span class="text-primary"><?php echo $NomePg; ?></span>
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
									
									<div class="push30"></div> 
				
									<div class="login-form">

										<div class="row">
											
											<div class="col-md-12">
					
												<?=html_entity_decode($des_termo)?>	

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
					
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	<script type="text/javascript">
		
        $(document).ready( function() {
			$('.dragTag').on('dragstart', function (event) {
			    var tag = $(this).attr('dragTagName');
			    event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
			    event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
			});


			$('.dragTag').on('click', function (event) {
			    var $temp = $("<input>");
			    $("#tosave").append($temp);
			    $temp.val($(this).text()).select();
			    document.execCommand("copy");
			    $temp.remove();
			});
			
        });

        function quickCopy(tag) {
		    var dummyContent = tag;
		    var dummy = $('<input>').val(dummyContent).appendTo('body');
		    dummy.select();
		    document.execCommand('copy');
		    dummy.remove();
		}

	</script>	
   