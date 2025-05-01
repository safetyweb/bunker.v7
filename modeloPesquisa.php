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

			$cod_categortkt = fnLimpaCampoZero($_REQUEST['COD_CATEGORTKT']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$des_categor = fnLimpaCampo($_REQUEST['DES_CATEGOR']);
			$des_abrevia = fnLimpaCampo($_REQUEST['DES_ABREVIA']);
			$des_icones = fnLimpaCampo($_REQUEST['DES_ICONES']);
			if (empty($_REQUEST['LOG_DESTAK'])) {$log_destak='N';}else{$log_destak=$_REQUEST['LOG_DESTAK'];}
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			//fnEscreve($des_icones);	
			
			if ($opcao != ''){
 
				$sql = "CALL SP_ALTERA_CATEGORIATKT (
				 '".$cod_categortkt."', 
				 '".$cod_empresa."', 
				 '".$des_categor."', 
				 '".$des_abrevia."', 
				 '".$des_icones."', 
				 '".$log_destak."', 
				 '".$_SESSION["SYS_COD_USUARIO"]."', 
				 '".$opcao."'    
				) ";
				
				
				//fnEscreve($sql);
				
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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];

		}
												
	}else {
		$cod_empresa = 0;		
		$nom_empresa = "";
	
	}
	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idP'])))){
	
	//busca dados do convênio
	$cod_pesquisa = fnDecode($_GET['idP']);	
	$sql = "SELECT * FROM PESQUISA WHERE COD_PESQUISA = ".$cod_pesquisa;	
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaTemplate)){
		$cod_pesquisa = $qrBuscaTemplate['COD_PESQUISA'];
		$log_ativo = $qrBuscaTemplate['LOG_ATIVO'];
		if ($log_ativo == "S") {$mostraLog_ativo="checked";}else{$mostraLog_ativo="";}
		$des_pesquisa = $qrBuscaTemplate['DES_PESQUISA'];
		$abr_pesquisa = $qrBuscaTemplate['ABR_PESQUISA'];
	}
		
	}else{
		$cod_pesquisa = "";
		$log_ativo = "";
		$des_pesquisa = "";
		$abr_pesquisa = "";
	}
	
	
	//liberação das abas
	$abaPersona	= "S";
	$abaVantagem = "S";
	$abaRegras = "S";
	$abaComunica = "N";
	$abaAtivacao = "N";
	$abaResultado = "N";

	//$abaPersonaComp = "completed ";
	$abaPersonaComp = " ";
	$abaVantagemComp = " ";
	$abaRegrasComp = " ";
	$abaComunicaComp = "active";
	$abaAtivacaoComp = "";
	$abaResultadoComp = "";	

	
	//fnMostraForm();
	//fnEscreve($sql);

?>

<style type="text/css">
    .template {
        margin: 0 auto;
        height: auto!important;
        width: 100%;
        margin-top: 50px;

    }

    .connectedSortable {
        list-style-type: none;
        padding: 0;
    }

    .connectedSortable li:not(.normal) {
        min-height: 60px;
        text-align: center;
        width: 80px;
		height: auto !important;
		overflow: hidden;
    }

    #sortable1 {
        float: left;
    }
	
	#sortable3 {
        float: right;
    }
	
	#sortable1 li, #sortable3 li {
        margin-top: 20px;
		border-radius: 5px;
		background-color: transparent;
		font-size: 25px !important;
    }

    #sortable2 {
        float: left;
        margin: 4px;
		height: auto !important;
		border: 3px dashed #cecece;
		padding: 10px;
		border-radius: 5px;
		width: 100%;
    }

    #sortable2 li {
        width: auto;
		background-color: #ffffff;
		border: none;
    }
	
	.ui-state-default {
		border: 1px solid #c5c5c5;
		background: #f6f6f6;
		font-weight: normal;
		color: #454545;
	}
	
	.ui-sortable-handle {
		touch-action: none;
	}
	
	.ui-state-default{
		border: none;
	}
	
	.ui-state-default a {
		color: #454545;
		text-decoration: none;
	}
	
	.descricaobloco{
		font-size: 11px;
	}
	
	.template i{
		margin-top: 10px;
	}
	
	hr{
		width: 100%;
		border-top: 2px solid #161616;
	}
	
	hr.divisao{
		width: 100%;
		border-top: 1px dashed #cecece;
		margin: 5px 0;
	}
	
	.excluirBloco:hover{
		color: #ff4a4a !important;
		cursor: pointer;
	}
	
	.addImagem{
		position: absolute;
		top: 20px;
		right: 0px;
		font-size: 16px;
		margin-right: 5px;
		color: #cccccc !important;
	}	
	
	.addImagem:hover{
		color: #18bc9c !important;
		cursor: pointer;
	}
	
	.imagemTicket {
		height:auto;
		width: 100%;  
		display: flex; 
		align-items: center; 
		justify-content: center;
		padding: 10px;
		padding-right: 20px;
	}

	/****** Style Star Rating Widget *****/

	.rating.rate10 { 
	  border: none;
	  float: left;
	  width: 280px;
	  text-align: right;  
	}
	
	.rating.rate5 { 
	  border: none;
	  float: left;
	  width: 230px;
	  text-align: right;  
	}	

	.rating > input { display: none; } 
	.rating > label:before { 
	  margin: 5px;
	  font-size: 1.25em;
	  font-family: FontAwesome;
	  display: inline-block;
	  content: "\f005";
	}
	
	.rating > label.radioType:before { 
	  margin: 5px;
	  font-size: 1.25em;
	  font-family: FontAwesome;
	  display: inline-block;
	  content: "\f192";
	}	

	.rating > .half:before { 
	  content: "\f089";
	  position: absolute;
	}

	.rating > label { 
	  color: #ddd; 
	 float: right; 
	}

	/***** CSS Magic to Highlight Stars on Hover *****/

	.rating > input:checked ~ label, /* show gold star when clicked */
	.rating:not(:checked) > label:hover, /* hover current star */
	.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */
	
	.rating > input:checked ~ label.radioType, /* show gold star when clicked */
	.rating:not(:checked) > label.radioType:hover, /* hover current star */
	.rating:not(:checked) > label.radioType:hover ~ label { color: #4286f4;  } /* hover previous stars in list */	

	.rating > input:checked + label:hover, /* hover current star when changing rating */
	.rating > input:checked ~ label:hover,
	.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
	.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
	
	.rating > input:checked + label.radioType:hover, /* hover current star when changing rating */
	.rating > input:checked ~ label.radioType:hover,
	.rating > label:hover ~ input.radioType:checked ~ label, /* lighten current selection */
	.rating > input:checked ~ label.radioType:hover ~ label { color:  #87b2f8;  } 	
	
	.bloco {
		padding: 15px 0;
	}
	
	.jconfirm .jconfirm-box {
		overflow: inherit;
	}
	
	.jconfirm .jconfirm-box div.jconfirm-content-pane {
		overflow: inherit;
	}	
	
	.delCondicao:hover {
		color: #2c3e50;
	}
	
	.blocoTexto, .blocoPergunta, .blocoAvaliacao {
		cursor: pointer;
	}
	
	.viewBody {
		float: left;
		margin: 4px;
		height: 500px;
		border: 3px dashed #4eb71d;
		padding: 10px;
		border-radius: 5px;
		width: 100%;
		margin-top: 18px;
	}

	/****** Style Star Rating Widget *****/
	
	.rating > label.totem { 
		font-size: 40px;
	}			

	.rating.rate10.totem { 
		border: none;
		float: left;
		width: 585px; 
	}
	
	.rating.rate5.totem { 
	  border: none;
	  float: left;
	  width: 430px;
	}	

	.rating > input { display: none; } 
	.rating > label:before { 
	  margin: 5px;
	  font-size: 1.25em;
	  font-family: FontAwesome;
	  display: inline-block;
	  content: "\f005";
	}
	
	.rating > label.radioType:before { 
	  margin: 5px;
	  font-size: 1.25em;
	  font-family: FontAwesome;
	  display: inline-block;
	  content: "\f192";
	}	

	.rating > .half:before { 
	  content: "\f089";
	  position: absolute;
	}

	.rating > label { 
	  color: #ddd; 
	  float: right; 
	}

	/***** CSS Magic to Highlight Stars on Hover *****/

	.rating > input:checked ~ label, /* show gold star when clicked */
	.rating:not(:checked) > label:hover, /* hover current star */
	.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */
	
	.rating > input:checked ~ label.radioType, /* show gold star when clicked */
	.rating:not(:checked) > label.radioType:hover, /* hover current star */
	.rating:not(:checked) > label.radioType:hover ~ label { color: #4286f4;  } /* hover previous stars in list */	

	.rating > input:checked + label:hover, /* hover current star when changing rating */
	.rating > input:checked ~ label:hover,
	.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
	.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
	
	.rating > input:checked + label.radioType:hover, /* hover current star when changing rating */
	.rating > input:checked ~ label.radioType:hover,
	.rating > label:hover ~ input.radioType:checked ~ label, /* lighten current selection */
	.rating > input:checked ~ label.radioType:hover ~ label { color:  #87b2f8;  } 		

	hr{
		width: 100%;
		border-top: 2px solid #161616;
	}
	
	hr.divisao{
		width: 100%;
		border-top: 1px dashed #cecece;
		margin: 5px 0;
	}	

	#footer {
		position: fixed;
		bottom: 0;
		width: 100%;				
	}
	
	.numero{
		font-size: 16px;
		margin-top: -10px;
		text-align: center;
	}		
	
	@media only screen and (min-width: 761px) and (max-width: 1281px) { /* 10 inch tablet enter here */
		.lead.titulo {
			margin-top: 50px;
		}
	} 			
	
	@media only screen and (max-width: 760px) {
		/* For mobile phones: */
		section#contact {
			padding: 10px 0;
		}
		
		.lead {
			margin-bottom: 10px;
		}				
		
		#footer .bottom-menu, #footer .bottom-menu-inverse {
			padding: 10px 0 0;
			height: 60px;
		}
		
		.rating > label { 
			font-size: 15px;
		}					
		
		.rating.rate10 { 
			border: none;
			float: left;
			width: 315px;
		}	
		.numero {
			margin-top: -10px;
		}				
	}

	#btnAtualizarPesquisa {
		margin-left: 5px;
	}

	.addBox:hover{
		cursor: pointer;
	}
</style>
			
<div class="push30"></div> 

<div class="row">

	<!-- Versão do fontawesome compatível com as checkbox (não remover) -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">				

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>
				
				<?php 
				$formBack = "1108";
				include "atalhosPortlet.php"; 
				?>	

			</div>
			<div class="portlet-body">
				
				<?php if ($msgRetorno <> '') { ?>	
				<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				 <?php echo $msgRetorno; ?>
				</div>
				<?php } ?>	
				
				<?php $abaCampanhas = 1254; include "abasCampanhasConfig.php"; ?>
				
				<div class="push30"></div> 

				<div class="login-form">
				
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
															
					<fieldset>
						<legend>Dados Gerais</legend> 
						
							<div class="row">
									<div class="col-md-2 col-sm-2 col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PESQUISA" id="COD_PESQUISA" value="<?php echo $cod_pesquisa ?>">
										</div>
									</div>
									
									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>
									
									<div class="col-md-4 col-sm-4 col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome da Pesquisa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_PESQUISA" id="DES_PESQUISA" value="<?php echo $des_pesquisa ?>" maxlength="20" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
								<div class="col-md-2 col-sm-2 col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ABR_PESQUISA" id="ABR_PESQUISA" value="<?php echo $abr_pesquisa ?>">
									</div>														
								</div>

								<div class="col-md-1 col-sm-1 col-xs-1">
									<div class="disabledBlock"></div>												
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label> 
										<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_DESTAK" id="LOG_DESTAK" class="switch" value="S" <?php echo $mostraLog_ativo; ?> >
											<span></span>
											</label>
									</div>
								</div>	
													
							</div>
					</fieldset>
					
					<div class="push30"></div> 
					
					<div class="row">
						<div class="col-md-4 col-sm-6 col-xs-6" >
							<h4>Monte aqui sua pesquisa</h4>
							
							<div class="push10"></div> 
							
							<div class="template">
								<div class="row">
									<div class="col-md-2 col-sm-2 col-xs-2">
										<ul id="sortable1" class="connectedSortable">
											<?php
												$sql = "SELECT * FROM BLOCOPESQUISA WHERE COD_BLPESQU ORDER BY NUM_ORDENAC";
												$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
											
												while ($qrListaBlocos = mysqli_fetch_assoc($arrayQuery))
												  {
													?>
												<li class="ui-state-default shadow grabbable" cod-registr="" cod-bloco="<?php echo $qrListaBlocos['COD_BLPESQU'] ?>" >
													<i class="fa <?php echo $qrListaBlocos['DES_ICONE'] ?>" aria-hidden="true"></i>
													<div class="descricaobloco"><?php echo $qrListaBlocos['ABV_BLPESQU'] ?></div>
												</li>
											<?php			
												  }											
											?>
										</ul>
									</div>
									<div class="col-md-10 col-sm-10 col-xs-10">
										Clique e arraste os blocos ao lado para montar sua pesquisa
										<ul id="sortable2" class="connectedSortable">
											<?php																	
												$sql = "SELECT * FROM   modelopesquisa
														WHERE  modelopesquisa.COD_EMPRESA = $cod_empresa 
														AND    modelopesquisa.COD_TEMPLATE = $cod_pesquisa
														AND    modelopesquisa.COD_EXCLUSA is null
														ORDER BY NUM_ORDENAC";
													
												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											
												while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery))
												  {
													  ?>
														<li class="ui-state-default" cod-registr="<?php echo $qrListaModelos['COD_REGISTR']?>" cod-bloco="<?php echo $qrListaModelos['COD_BLPESQU']?>">
													  <?php
													  	switch ($qrListaModelos['COD_BLPESQU']) {	
															case 1:// TEXTO INFORMATIVO
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10 col-sm-10 col-md-xs blocoTexto">
																			<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA'];?></label>
																			<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA'];?>">
																		</div>
																		<div class="col-md-2 col-sm-2 col-xs-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>
															<?php
															break;     
															case 2:// PERGUNTA
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10 col-sm-10 col-md-xs blocoPergunta">
																			<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA'];?></label>
																			<input type="text" class="form-control input-sm" value="">
																			<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA'];?>">
																		</div>
																		<div class="col-md-2 col-sm-2 col-xs-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>																
															<?php
															break; 				
															case 3:// SALDO DE PONTOS
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10 col-sm-10 col-md-xs">
																			<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
																			<h6>Número Cartão: 1234 5678 9012 3456</h6>
																			<h6>Saldo: R$ 0,18  31/05/2017</h6>
																		</div>
																		<div class="col-md-2 col-sm-2 col-xs-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>																
															<?php
															break; 				
															case 4: // IMAGEM
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10 col-sm-10 col-md-xs">
																			<div class="div-imagem">
																				<?php
																					if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
																						?>
																						<div class="imagemTicket">
																							<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
																							<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR'];?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
																						</div>
																						<?php
																					}else{
																						?>
																						<div  class="imagemTicket">
																							<img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM'];?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
																							<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR'];?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
																						</div>
																						<?php
																					}
																				?>
																			</div>
																		</div>
																		<div class="col-md-2 col-sm-2 col-xs-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>																	
															<?php
															break;		
															case 5:// AVALIAÇÃO
															?>
															<center class="bloco">
																<div class="row">
																	<div class="col-md-10 col-sm-10 col-md-xs blocoAvaliacaoComentado addBox" data-url="action.php?mod=<?php echo fnEncode(1509)?>&id=<?php echo fnEncode($cod_empresa)?>&idp=<?=fnEncode($cod_pesquisa)?>&pop=true" data-title="Bloco de Avaliação">
																		<h5><?php echo $qrListaModelos['DES_PERGUNTA'];?></h5>
																		<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA'];?>">												
																		<input type="hidden" class="tip_bloco" value="<?php echo $qrListaModelos['TIP_BLOCO'];?>">												
																		<input type="hidden" class="num_quantid" value="<?php echo $qrListaModelos['NUM_QUANTID'];?>">												
																		<input type="hidden" class="log_condicoes" value='<?php echo $qrListaModelos['LOG_CONDICOES'];?>'>
																		<input type="hidden" class="log_principal" value='<?php echo $qrListaModelos['LOG_PRINCIPAL'];?>'>
																		<fieldset class="rating rate<?php echo $qrListaModelos['NUM_QUANTID']; ?>">
																		<?php
																			$contador = 0;
																			while ($contador < $qrListaModelos['NUM_QUANTID']) {
																				?>
																					<input type="radio" name="rating" value="5" /><label class= "star<?php echo $contador;?> <?php echo $qrListaModelos['TIP_BLOCO'];?>Type full" for="star"></label>
																				<?php
																				$contador++;
																			}																		
																		
																		?>
																		</fieldset>	
																	</div>
																	<div class="col-md-2 col-sm-2 col-xs-2">
																		<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																	</div>																	
																</div>															
															</center>
															<hr class="divisao"/>																
															<?php
															break;	
															case 6:// LOGIN
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10 col-sm-10 col-md-xs" style="padding: 0 0 0 50px;">
																			<header>
																				<p class="lead">Faça seu login para responder nossas pesquisas!</p>
																			</header>
																			<div class="row">
																				<div class="col-md-12 col-sm-12 col-md-xs">
																					<input type="text" id="cpf" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
																					<div class="push10"></div>
																					<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
																					<div class="push10"></div>
																					<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-2 col-sm-2 col-xs-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>
															<?php
															break;	
															case 7:// LOGIN COM SENHA
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10 col-sm-10 col-md-xs" style="padding: 0 0 0 50px;">
																			<header>
																				<p class="lead">Faça seu login para responder nossas pesquisas!</p>
																			</header>
																			<div class="row">
																				<div class="col-md-12 col-sm-12 col-md-xs">
																					<input type="text" id="cpf" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
																					<div class="push10"></div>
																					<input type="password" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha">
																					<div class="push10"></div>
																					<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
																					<div class="push10"></div>
																					<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-2 col-sm-2 col-xs-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>
															<?php
															break;
															case 8:// SMART LOGIN
															?>
																<center class="bloco">
																	<div class="row">
																		<div class="col-md-10">
																			<div class="col-md-4 text-center"><span class="fa fa-envelope"></span></div>
																			<div class="col-md-4 text-center"><span class="fa fa-phone"></span></div>
																			<div class="col-md-4 text-center"><span class="fa fa-user"></span></div>
																		</div>
																		<div class="col-md-2">
																			<a class="excluirBloco"><i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i></a>
																		</div>																	
																	</div>															
																</center>
																<hr class="divisao"/>
															<?php
															break;															
														}
														
														?>
															</li>
														<?php
												  }											
											?>
										</ul>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-8 col-sm-6 col-xs-6">
							<div class="col-md-12">
								<h4>Visualize sua pesquisa</h4>
							</div>

							<div class="col-md-8">
								<div class="col-md-4">
									<button type="button" class="btn btn-info" id="btnAtualizarPesquisa"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Atualizar Visualização</button>
								</div>

								<div class="col-md-8">
									<input type="text" id="linkPesquisa" class="form-control input-md pull-right" value="https://modelo.fidelidade.mk/pesquisa?idP=<?=fnEncode($cod_pesquisa)?>" readonly>
								</div>	
							</div>

							<div class="col-md-4">

								<div class="col-md-6">
									<button type="button" class="btn btn-default" id="btnPesquisa"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp; Copiar Link</button>
									<script type="text/javascript">
										$("#btnPesquisa").click(function(){
											if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
												var el = $("#linkPesquisa").get(0);
												var editable = el.contentEditable;
												var readOnly = el.readOnly;
												el.contentEditable = true;
												el.readOnly = false;
												var range = document.createRange();
												range.selectNodeContents(el);
												var sel = window.getSelection();
												sel.removeAllRanges();
												sel.addRange(range);
												el.setSelectionRange(0, 999999);
												el.contentEditable = editable;
												el.readOnly = readOnly;
											} else {
												$("#linkPesquisa").select();
											}
											document.execCommand('copy');
											$("#linkPesquisa").blur();
											$("#btnPesquisa").text("Link Copiado");
										});
									</script>
								</div>

								<div class="col-md-6">
									<a href="https://modelo.fidelidade.mk/pesquisa?idP=<?=fnEncode($cod_pesquisa)?>" class="btn btn-default pull-right" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp; Acessar Pesquisa</a>
								</div>	

							</div>

							<div class="push20"></div>

							<div class="col-md-12">
							
								<div class="viewBody" style="height: 720px;" >
									<div class="col-md-12 col-sm-12 col-xs-12">
										<header>
											<h4 class="lead titulo"><b>Pesquisa</b></h4>
										</header>
										<hr class="divisao"/>
										<div class="row">
											<div class="col-md-6 col-sm-6 col-xs-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">
												<div id="blocoPesquisa">
													
												</div>
											</div><!-- /col-md-6 col-sm-6 col-xs-6-->
										</div>
									</div>							
								</div>

							</div>

						</div>

					</div>

							
					<div class="100"></div>
					
					<input type="hidden" name="REFRESH_CONDICAO" id="REFRESH_CONDICAO" value="N">
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
					
					<div class="push5"></div>					
					
					</form>
					
					<div class="push100"></div>
					
					
				
				</div>								
			
			</div>
		</div>
		<!-- fim Portlet -->
	</div>
</div>	


<!-- modal -->									
<div class="modal fade" id="popModal" tabindex='-1' style="width: 700px; margin: auto;">
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 85%"></iframe>
				</div>		
			</div>
		</div>
	</div>	
	
	
<div class="push20"></div> 
				
<script src="js/jquery-ui.js"></script>

<script type="text/javascript">
	
	$(document).ready( function() {

		//modal close
		$('.modal').on('hidden.bs.modal', function () {
		  if($('#REFRESH_CONDICAO').val() == "S"){
			$('#REFRESH_CONDICAO').val("N");				
		  }	
		});
		
		$('body').on('click', '.upload-image', function() {
			$(this).siblings().click();
		});
		
		$('body').on('change', '.image-file', function() {
			var formData = new FormData();
			formData.append('arquivo', $(this)[0].files[0]);
			formData.append('id', $('#COD_EMPRESA').val());
			formData.append('cod_registr', $(this).attr('cod_registr'));
			
			salvarConfigBloco("", "", "0", "", "", $(this).attr('cod_registr'), $(this)[0].files[0].name);
			
			var div_imagem = $(this).parent().parent();
			
			$.ajax({
				url : 'uploads/uploadpro.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success : function(data) {
					div_imagem.html(data);
				}
			});
		});
		
		// //icon picker
		// $('.btnSearchIcon').iconpicker({ 
		// 	cols: 8,
		// 	iconset: 'fontawesome',   
		// 	rows: 6,
		// 	searchText: 'Procurar  &iacute;cone'
		// });	
		
		// $('.btnSearchIcon').on('change', function(e) { 
		// 	$("#DES_ICONES").val(e.icon);		
		// });	
		
		// Builder
        $('#sortable2').css('min-height', $('#sortable1').height());
		//$('#sortable2').width($('#sortable2').parent().width() - 20);

        var altura = $('.template').height();
        var itens = $('#sortable1 li').length;

        $('#sortable1 > li').css('height', (altura - 10) / itens - 2);

        var listaHeight = $('#sortable2').height();
        var listaContent = 0;

        $("#sortable1").sortable({
            connectWith: ".connectedSortable",
            remove: function (event, ui) {
				var idTem = <?php echo $cod_pesquisa ?>;
				var idEmp = <?php echo $cod_empresa ?>;
				var codBloco = ui.item.attr('cod-bloco');
				var cod_registr = ""
				
				// Adicionar modelo template
				$.ajax({
					type: "GET",
					url: "ajxBlocoModeloPesquisa.do",
					data: { cod_empresa:idEmp, opcao:0, ajx3:idTem, ajx4:codBloco},
					success:function(data){
						cod_registr = data.trim();
						var indice = ui.item.index();
						ui.item.clone().attr('cod-registr', data.trim()).removeClass('shadow').insertBefore($('#sortable2 li').eq(indice));
						$('#sortable1').sortable('cancel');
						console.log(data);
						// console.log("depois desse, tem outro ajax");
						
						// Retorna info li
						$.ajax({
							type: "GET",
							url: "ajxBlocoModeloPesquisa.do",
							data: { cod_empresa:idEmp, opcao:codBloco, ajx3: idTem, ajx4:cod_registr},
							beforeSend:function(){
								$('#sortable2 li[cod-registr=' +cod_registr+ ']').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$('#sortable2 li[cod-registr=' +cod_registr+ ']').html(data);  
								ordenar();
								console.log(data);
							},
							error:function(data){
								$('#sortable2 li[cod-registr=' +cod_registr+ ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								console.log(data);
							}
						});
					},
				});
            }
        }).disableSelection();
		
		
        $("#sortable3").sortable({
            connectWith: ".connectedSortable",
            remove: function (event, ui) {
				var idTem = <?php echo $cod_pesquisa ?>;
				var idEmp = <?php echo $cod_empresa ?>;
				var codBloco = ui.item.attr('cod-bloco');
				var cod_registr = ""
				
				// Adicionar modelo template
				$.ajax({
					type: "GET",
					url: "ajxBlocoModeloPesquisa.do",
					data: { cod_empresa:idEmp, opcao:0, ajx3:idTem, ajx4:codBloco},
					success:function(data){
						cod_registr = data.trim();
						var indice = ui.item.index();
						ui.item.clone().attr('cod-registr', data.trim()).removeClass('shadow').insertBefore($('#sortable2 li').eq(indice));
						$('#sortable3').sortable('cancel');
						console.log(data);
						// console.log("depois desse, tem outro ajax");
						// Retorna info li
						$.ajax({
							type: "GET",
							url: "ajxBlocoModeloPesquisa.do",
							data: { cod_empresa:idEmp, opcao:codBloco, ajx3: idTem, ajx4:cod_registr},
							beforeSend:function(){
								$('#sortable2 li[cod-registr=' +cod_registr+ ']').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$('#sortable2 li[cod-registr=' +cod_registr+ ']').html(data);  
								ordenar();
								console.log(data);
							},
							error:function(data){
								$('#sortable2 li[cod-registr=' +cod_registr+ ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								console.log(data);
							}
						});
					},
				});
            }
        }).disableSelection();		

        $("#sortable2").sortable({
            connectWith: ".connectedSortable",
			stop: function(event, ui) {
				ordenar();
			}
        }).disableSelection();	
		
		$('body').on('click', '.excluirBloco', function() {
			var cod_registr = $(this).parents('.ui-state-default').attr('cod-registr');
			var _this = $(this).parents('.ui-state-default');
			var idEmp = <?php echo $cod_empresa ?>;
		  
			$.ajax({
				type: "GET",
				url: "ajxBlocoModeloPesquisa.do",
				data: { cod_empresa:idEmp, opcao:99, ajx3:cod_registr},
				beforeSend:function(){
					$('#sortable2 li[cod-registr=' +cod_registr+ ']').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					_this.remove();
					$('#sortable2 li[cod-registr=' +cod_registr+ ']').html(data);
					console.log(data);
				},
				error:function(data){
					$('#sortable2 li[cod-registr=' +cod_registr+ ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					console.log(data);
				}
			});
		});		
		
		$('body').on('click', '.blocoTexto', function() {
			var cod_registr = $(this).parents('.ui-state-default').attr('cod-registr');
			var thisTexto = $(this);
			
			var des_pergunta = $(this).find('.des_pergunta').val() == undefined ? "" : $(this).find('.des_pergunta').val();
			
			$.confirm({
				icon: 'fa fa-text-height',
				title: 'Tipo Texto',
				content: '' +
				'<input type="text" placeholder="Seu texto" maxlenght="200" class="texto form-control input-sm" value="'+des_pergunta+'" />',
				buttons: {
					formSubmit: {
						text: 'Salvar',
						btnClass: 'btn-blue',
						action: function () {
							var texto = this.$content.find('.texto').val();
							if(!texto){
								$.alert('Por favor, digite a pergunta!');
								return false;
							}
							thisTexto.text(texto);
							
							salvarConfigBloco(texto, "", "0", "", "", cod_registr, "");
						}
					},
					cancelar: function () {
						//close
					},
				}
			});			
		});		
		
		$('body').on('click', '.blocoPergunta', function() {
			var cod_registr = $(this).parents('.ui-state-default').attr('cod-registr');
			var thisTexto = $(this);
			
			var des_pergunta = $(this).find('.des_pergunta').val() == undefined ? "" : $(this).find('.des_pergunta').val();
			
			$.confirm({
				icon: 'fa fa-question-circle',
				title: 'Tipo Pergunta',
				content: '' +
				'<input type="text" placeholder="Seu texto" maxlenght="200" class="texto form-control input-sm" value="'+des_pergunta+'" />',
				buttons: {
					formSubmit: {
						text: 'Salvar',
						btnClass: 'btn-blue',
						action: function () {
							var texto = this.$content.find('.texto').val();
							if(!texto){
								$.alert('Por favor, digite a pergunta!');
								return false;
							}
							thisTexto.text(texto);
					
							salvarConfigBloco(texto, "", "0", "", "", cod_registr, "");
						}
					},
					cancelar: function () {
						
					},
				}
			});			
		});

	});			

		
	function salvarConfigBloco(pPergunta, pTipo, pQtde, pLogPrincipal, pCondicoes, pCod_registr, pDes_imagem){
		$.ajax({
			type: "GET",
			url: "ajxBlocoModeloPesquisa.do",
			data: {opcao:'alterarBloco', des_imagem: pDes_imagem, log_principal: pLogPrincipal, pergunta: pPergunta, tipo: pTipo, qtde: pQtde, condicoes: pCondicoes, cod_registr: pCod_registr, cod_empresa: <?php echo $cod_empresa ?>},
			success:function(data){
				console.log(data);
			},
		});		
	}

	
	function ordenar(){
		var ids = "";
		$('#sortable2 li.ui-state-default').each(function( index ) {
			ids += $(this).attr('cod-registr') + ",";
		});
		
		var arrayOrdem = ids.substring(0,(ids.length-1));
		execOrdenacao(arrayOrdem,6);

		function execOrdenacao(p1,p2) {
			var codEmpresa = <?php echo $cod_empresa ?>;
			$.ajax({
				type: "GET",
				url: "ajxOrdenacaoEmp.do",
				data: { ajx1:p1, ajx2:p2, ajx3:codEmpresa},
				beforeSend:function(){
					//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					//$("#divId_sub").html(data); 
					//console.log(data);
				},
				error:function(){
					//$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
				}
			});		
		}
	}
	
	
	/**
		INÍCIO CÓDIGO FONTE REFERENTE AO VISUALIZADOR DE PESQUISA
	*/
	
	ajxIniciarPesquisas(<?php echo $cod_pesquisa ?>);
	
	$('body').on('click', '.btnContinuar', function() {
		proximoBlocoSemSalvar($(this));
	});
	
	$('body').on('click', '#btnAtualizarPesquisa', function() {
		ajxIniciarPesquisas(<?php echo $cod_pesquisa ?>);
	});	
	
	function proximoBlocoSemSalvar(_this){
		var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
		var pCodPesquisa = _this.attr('cod-pesquisa');
		var pCodRegistro = _this.attr('cod-registro');						
		$.ajax({
			type: "GET",
			url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
			data: { opcao: 'proximoBlocoPesquisa', cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_ordenacao: pCodOrdenacao, cod_empresa: <?php echo $cod_empresa; ?> },
			beforeSend:function(){
				$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
			},						
			success: function(data) {
				$('#blocoPesquisa').html(data);
			}
		});				
	}		
	
	function ajxIniciarPesquisas(pCodPesquisa){
		$.ajax({
			type: "GET",
			url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
			data: { opcao: 'iniciarPesquisaVisualizacao', cod_pesquisa: pCodPesquisa, cod_empresa: <?php echo $cod_empresa; ?> },
			beforeSend:function(){
				$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
			},						
			success: function(data) {
				$('#blocoPesquisa').html(data);
			}
		});				
	}	
	
	/**
		FINAL CÓDIGO FONTE REFERENTE AO VISUALIZADOR DE PESQUISA
	*/	
	
</script>	