<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	$mostraChecadoAT = "checked";				
	$mostraChecadoRT = "";
	$cod_desafio = 0;	
	
	//verifica se vem da tela sem pop up
	if (is_null($_GET['idp'])) {
		$log_preTipo='N';}
		else{$log_preTipo='S'; 
			$cod_preTipo = fnDecode($_GET['idp']);
		}
				
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

			$cod_desafio = fnLimpaCampoZero($_REQUEST['COD_DESAFIO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_persona = $_REQUEST['COD_PERSONA'];
			$nom_desafio = fnLimpaCampo($_REQUEST['NOM_DESAFIO']);
			$des_desafio = addslashes(htmlentities($_REQUEST['DES_DESAFIO']));
			$dat_ini = fnLimpaCampo($_REQUEST['DAT_INI']);
			$dat_fim = fnLimpaCampo($_REQUEST['DAT_FIM']);
			$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
			$des_cor = fnLimpaCampo($_REQUEST['DES_COR']);
			$val_metades = fnLimpaCampo($_REQUEST['VAL_METADES']);
			$tip_divisao = fnLimpaCampo($_REQUEST['TIP_DIVISAO']);
			$tip_geracao = fnLimpaCampo($_REQUEST['TIP_GERACAO']);

			if (isset($_POST['COD_UNIVEND'])){
				$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];
				//print_r($Arr_COD_MULTEMP);			 
			 
			   for ($i=0;$i<count($Arr_COD_UNIVEND);$i++) 
			   { 
				$cod_univend = $cod_univend.$Arr_COD_UNIVEND[$i].",";
			   } 
			   
			   $cod_univend = ltrim(rtrim($cod_univend,','),',');
				
			}else{$cod_univend = "0";}

			if (isset($_POST['COD_USUARIO'])){
				$Arr_COD_USUARIO = $_POST['COD_USUARIO'];
				//print_r($Arr_COD_MULTEMP);			 
			 
			   for ($i=0;$i<count($Arr_COD_USUARIO);$i++) 
			   { 
				$cod_usuario = $cod_usuario.$Arr_COD_USUARIO[$i].",";
			   } 
			   
			   $cod_usuario = ltrim(rtrim($cod_usuario,','),',');
				
			}else{$cod_usuario = "0";}

			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			if (empty($_REQUEST['LOG_EMAIL'])) {$log_email='N';}else{$log_email=$_REQUEST['LOG_EMAIL'];}
			if (empty($_REQUEST['LOG_SMS'])) {$log_sms='N';}else{$log_sms=$_REQUEST['LOG_SMS'];}
			if (empty($_REQUEST['LOG_WPP'])) {$log_wpp='N';}else{$log_wpp=$_REQUEST['LOG_WPP'];}
			if (empty($_REQUEST['LOG_PUSH'])) {$log_push='N';}else{$log_push=$_REQUEST['LOG_PUSH'];}
			if (empty($_REQUEST['LOG_NPS'])) {$log_nps='N';}else{$log_nps=$_REQUEST['LOG_NPS'];}
			
			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			//fnEscreve($cod_empresa);
						
			if ($opcao != ''){					
				// //fnEscreve($qrBuscaNovo["COD_NOVO"]);				
				// $cod_desafio = $qrBuscaNovo["COD_NOVO"];

				//atualiza lista iframe				
				?>
				
				<script>
					try { parent.$('#REFRESH_DESAFIO').val("S"); } catch(err) {}
				</script>	
				
				<?php
				 		
				 
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						
						$connTemporaria = connTemp($cod_empresa,'');	
						$sql = "INSERT INTO DESAFIO(
											COD_EMPRESA,
											COD_UNIVEND,
											COD_PERSONA,
											NOM_DESAFIO,
											DES_DESAFIO,
											DAT_INI,
											DAT_FIM,
											DES_ICONE,
											DES_COR,
											VAL_METADES,
											TIP_DIVISAO,
											COD_USUARIO,
											TIP_GERACAO,
											LOG_EMAIL,
											LOG_SMS,
											LOG_WPP,
											LOG_PUSH,
											LOG_NPS,
											COD_USUCADA,
											LOG_ATIVO
											) VALUES(
											$cod_empresa,
											'$cod_univend',
											$cod_persona,
											'$nom_desafio',
                                            '$des_desafio',											
											'".fnDataSql($dat_ini)."',
											'".fnDataSql($dat_fim)."',
											'$des_icone',
											'$des_cor',
											'".fnValorsql($val_metades)."',
											'$tip_divisao',
											'$cod_usuario',
                                            '$tip_geracao',    
											'$log_email',
											'$log_sms',
											'$log_wpp',
											'$log_push',
											'$log_nps',
											$cod_usucada,
											'$log_ativo'
											)";
				
						//fnEscreve($sql);
						$connNovoId = mysqli_query($connTemporaria,$sql);
						
						//busca código do desafio
						$novoId = mysqli_insert_id($connTemporaria);
						//fnEscreve($novoId);
						mysqli_close($connTemporaria);
						
						//copia personas para lista do desafio	
						$sql = "INSERT 
								INTO DESAFIO_CONTROLE (COD_EMPRESA, COD_PERSONA, COD_DESAFIO, COD_CLIENTE,COD_UNIVEND)
								SELECT P.COD_EMPRESA,P.COD_PERSONA, $novoId, P.COD_CLIENTE,C.COD_UNIVEND 
								FROM PERSONACLASSIFICA P 
								inner join CLIENTES C ON C.COD_CLIENTE=P.COD_CLIENTE 
								WHERE P.COD_PERSONA = $cod_persona  AND P.COD_EMPRESA=".$cod_empresa;
				
						//fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);
						
						//busca código do desafio
						$sql = "select MAX(COD_DESAFIO) AS COD_DESAFIO 
								 from  DESAFIO_CONTROLE 
								 where cod_empresa = $cod_empresa  ";						
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());	
						$qrBuscaDesafio = mysqli_fetch_assoc($arrayQuery);                     						
						$cod_desafio = $qrBuscaDesafio['COD_DESAFIO'];

						$sql1 = "CALL SP_RATEIO_DESAFIO(
								'".$cod_empresa."',
								'".$cod_desafio."',   
								'".$opcao."'   
						);";

						fnEscreve($sql1);

						$execLista = mysqli_query(connTemp($cod_empresa,''),$sql1);
						$qrGravaLista = mysqli_fetch_assoc($execLista);						
												
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

					$sql = "UPDATE DESAFIO SET
									COD_PERSONA=$cod_persona,
									COD_UNIVEND='$cod_univend',
									NOM_DESAFIO='$nom_desafio',
									DES_DESAFIO='$des_desafio',
									DAT_INI='".fnDataSql($dat_ini)."',
									DAT_FIM='".fnDataSql($dat_fim)."',
									DES_ICONE='$des_icone',
									DES_COR='$des_cor',
									VAL_METADES='".fnValorsql($val_metades)."',
									TIP_DIVISAO='$tip_divisao',
									TIP_GERACAO='$tip_geracao',
									COD_USUARIO='$cod_usuario',
									LOG_EMAIL='$log_email',
									LOG_SMS='$log_sms',
									LOG_WPP='$log_wpp',
									LOG_PUSH='$log_push',
									LOG_NPS='$log_nps',
									COD_ALTERAC=$cod_usucada,
									DAT_ALTERAC=NOW(),
									LOG_ATIVO='$log_ativo'
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_DESAFIO = $cod_desafio";
				
						//fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);
						
						$sql1 = "CALL SP_RATEIO_DESAFIO(
								'".$cod_empresa."',
								'".$cod_desafio."',   
								'".$opcao."'   
						);";

						//fnEscreve($sql1);

						$execLista = mysqli_query(connTemp($cod_empresa,''),$sql1);
						$qrGravaLista = mysqli_fetch_assoc($execLista);						
						

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
	
	//defaul - perfil
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);                     
			
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;
		$cod_desafio = 0;		
		//fnEscreve('entrou else');
		$log_ativo = "N";
		$mostraChecado = "checked";
		$mostraChecadoATU = "checked";		
		$nom_desafio = "";
		$abr_campanha = "";
		$des_icone = "";
		$des_cor = "";
		$des_observa = "";	
		$tip_campanha = "";	
		$log_continu = "N";
		$dat_ini = "";
		$hor_ini = "";
		$dat_fim = "";
		$hor_fim = "";
	}

	
	if ($cod_desafio == 0) {$cod_desafio = fnDecode($_GET['idc']);}
	//fnEscreve($cod_desafio);
	if ($cod_desafio != 0){
		$sql = "SELECT * FROM DESAFIO WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio";
		 
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(ConnTemp($cod_empresa,''),$sql);
		$qrBuscaDesafio = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)){
			//fnEscreve('query busca');
			$log_ativo = $qrBuscaDesafio['LOG_ATIVO'];
			if ($log_ativo == "S"){ $mostraAtivo = "checked";}
			else {$mostraAtivo = "";}	

			$log_email = $qrBuscaDesafio['LOG_EMAIL'];
			if ($log_email == "S"){ $mostraEmail = "checked";}
			else {$mostraEmail = "";}

			$log_sms = $qrBuscaDesafio['LOG_SMS'];
			if ($log_sms == "S"){ $mostraSms = "checked";}
			else {$mostraSms = "";}

			$log_wpp = $qrBuscaDesafio['LOG_WPP'];
			if ($log_wpp == "S"){ $mostraWpp = "checked";}
			else {$mostraWpp = "";}

			$log_push = $qrBuscaDesafio['LOG_PUSH'];
			if ($log_push == "S"){ $mostraPush = "checked";}
			else {$mostraPush = "";}

			$log_nps = $qrBuscaDesafio['LOG_NPS'];
			if ($log_nps == "S"){ $mostraNps = "checked";}
			else {$mostraNps = "";}

			$cod_persona = $qrBuscaDesafio['COD_PERSONA'];
			$cod_univend = $qrBuscaDesafio['COD_UNIVEND'];
			$cod_usuario = $qrBuscaDesafio['COD_USUARIO'];
			$nom_desafio = $qrBuscaDesafio['NOM_DESAFIO'];
			$des_desafio = $qrBuscaDesafio['DES_DESAFIO'];
			$dat_ini = $qrBuscaDesafio['DAT_INI'];
			$dat_fim = $qrBuscaDesafio['DAT_FIM'];
			$des_icone = $qrBuscaDesafio['DES_ICONE'];
			$des_cor = $qrBuscaDesafio['DES_COR'];
			$val_metades = $qrBuscaDesafio['VAL_METADES']; 
			$tip_divisao = $qrBuscaDesafio['TIP_DIVISAO']; 
			$tip_geracao = $qrBuscaDesafio['TIP_GERACAO']; 

		}
	
	}else{
		
		//fnEscreve('sem query busca');
		$cod_desafio = 0;
		$cod_univend = 0;
		$cod_usuario = 0;
		$log_ativo = "N";
		$log_email = "N";
		$log_sms = "N";
		$log_wpp = "N";
		$log_push = "N";
		$log_nps = "N";
		$mostraAtivo ='';
		$mostraEmail ='';
		$mostraSms ='';
		$mostraWpp ='';
		$mostraPush ='';
		$cod_persona ='';
		$nom_desafio ='';
		$des_desafio ='';
		$dat_ini ='';
		$dat_fim ='';
		$des_icone ='';
		$des_cor ='';
		$val_metades ='';
	}

	if($tip_divisao == "USER"){
		$displayUsu = "";
	}else{
		$displayUsu = "display: none;";
	}
	
	if(trim($cod_univend) != ""){
		$displayBlocked = "block";
	}else{
		$displayBlocked = "none";
	}
	//fnEscreve($qrBuscaNovo["COD_NOVO"]);
	// fnEscreve($cod_empresa);
	//fnMostraForm();

?>

<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode: "textareas",
        language: "pt",
        theme: "advanced",
		height : "400",
        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,

        // Example content CSS (should be your site CSS)
        //content_css : "css/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js",

        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }
    });
</script>

<style type="text/css">
	.chosen-container{
		width: 100%!important;
	}
	#blocker
	{
	    display:none; 
		position: fixed;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    opacity: .8;
	    background-color: #fff;
	    z-index: 1000;
	    cursor: wait;
	}
	    
	#blocker div
	{
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

					<div id="blocker">
				       <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
				    </div>

					<?php if ($popUp != "true"){ ?>
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
									
									<?php if ($log_preTipo =='S') { ?>	
									<div class="alert alert-warning top30 bottom30" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 Informe os dados para o preenchimento da sua <strong>Desafio</strong>. 
									</div>
									<?php } ?>
		
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

										
										<ul class="nav nav-tabs">
										  <li class="active"><a data-toggle="tab" href="#geral">Dados Gerais</a></li>
										  <li><a data-toggle="tab" href="#unidades">Unidades</a></li>
										  <li><a data-toggle="tab" href="#roteiro">Script do Desafio</a></li>
										  <!--<li><a data-toggle="tab" href="#comunicacao">Comunicação</a></li>-->
										</ul>										
																	
										<div class="tab-content">
											<!-- aba geral -->
											<div id="geral" class="tab-pane active">
											
												<div class="push30"></div>
											
												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Desafio Ativo</label> 
																<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $mostraChecadoAT; ?> >
																<span></span>
																</label>
														</div>
													</div>
													
												</div>
												
												<div class="push10"></div> 
													
												<div class="row">
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DESAFIO" id="COD_DESAFIO" value="<?php echo $cod_desafio; ?>">
														</div>
													</div>
													
													<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
													
													<div class="col-md-5">
														<div class="form-group">
															<label for="inputName" class="control-label required">Título do Desafio</label>
															<input type="text" class="form-control input-sm" name="NOM_DESAFIO" id="NOM_DESAFIO" value="<?php echo $nom_desafio; ?>" required>
														</div>														
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnDataShort($dat_ini); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Final</label>
															
															<div class="input-group date datePicker" id="DAT_FIM_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnDataShort($dat_fim); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													
												</div>
												
												<div class="push10"></div> 
												
												<div class="row">	
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Ícone</label><br/>
																<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" 
																	data-icon="<?php echo $des_icone ?>" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right"
																	data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
																</button>
															<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="<?php echo $des_icone ?>">
														</div> 
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cor</label>
															<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>">															
														</div>
														<div class="help-block with-errors"></div>														
													</div>
														
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Meta do Desafio</label> 
															<input type="text" class="form-control text-center calcula input-sm money" name="VAL_METADES" id="VAL_METADES" maxlength="5"  value="<?php echo fnValor($val_metades,2); ?>" data-error="Campo obrigatório" required >
															<div class="help-block with-errors">(%) percentual</div>
														</div>														
													</div>
															
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Persona Participante da Desafio</label>
															
																<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA" id="COD_PERSONA" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																	<?php
																	//se venda em tempo real
																	//$sql = "select * from persona where cod_empresa = ".$cod_empresa." order by DES_PERSONA  ";																		
																	
																	 $sql = "select ifnull(personaregra.COD_REGRA,0) as TEM_REGRA, persona.* 
																			 from persona 
																			LEFT JOIN  personaregra ON  personaregra.cod_persona = persona.cod_persona
																			 where cod_empresa = $cod_empresa order by DES_PERSONA ";																		
																	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());																
																	while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																	  {	
                                                                                                                                             
																		if ( $qrListaPersonas['LOG_ATIVO'] == "N" || $qrListaPersonas['TEM_REGRA'] == "0" )
                                                                            {$desabilitado = "disabled";}else{$desabilitado = "";}
																																				
																		echo"
																			  <option value='".$qrListaPersonas['COD_PERSONA']."' ".$desabilitado.">".ucfirst($qrListaPersonas['DES_PERSONA'])."</option> 
																			"; 
																		  }	
																	?>								
																</select>
																<span class="help-block"><?php echo $msgPersona; ?></span>																
																<div class="help-block with-errors"></div>
																<script>
																	$("#formulario #COD_PERSONA").val('<?=$cod_persona?>').trigger("chosen:updated");
																</script>	
														</div>
													</div>
													
												</div>
												
												<div class="push10"></div>

												<div class="row">

													<div class="col-md-12">
														<div class="form-group">
															<!-- <div class="disabledBlock" id="blockUnivend" style="display: <?=$displayBlocked?>;"></div> -->
															<label for="inputName" class="control-label required">Unidade de Atendimento</label>
															<?php include "unidadesAutorizadasComboMulti.php"; ?>
														</div>
													</div>

												</div>

												<div class="push10"></div>
												
												<div class="row">
															
													<div class="col-md-6">
														<div class="form-group">
															<!-- <div class="disabledBlock" id="blockLista" style="display: <?=$displayBlocked?>;"></div> -->
															<label for="inputName" class="control-label required">Lista gerada por:</label>
															
																<select data-placeholder="Selecione a divisão" name="TIP_GERACAO" id="TIP_GERACAO" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
															
																	<option value=""></option> 
																	<option value="CAD">Loja de cadastro</option> 
																	<option value="PREF">Loja de Preferência</option> 
															
																</select>
																<span class="help-block"><?php echo $msgPersona; ?></span>																
																<div class="help-block with-errors"></div>
																<script>
																	$("#formulario #TIP_GERACAO").val('<?=$tip_geracao?>').trigger("chosen:updated");
																</script>
															<div class="help-block with-errors">Com base na persona</div>
														</div>
													</div>	
													
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Divisão da Lista</label>
															
																<select data-placeholder="Selecione a divisão" name="TIP_DIVISAO" id="TIP_DIVISAO" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
															
																	<option value=""></option> 
																	<option value="LOJA">Distribuir igualitariamente por vendedores por loja</option> 
																	<option value="VEND">Priorizar vendedores vinculados à última venda</option> 
																	<option value="USER">Usuários Específicos</option> 
															
																</select>
																<span class="help-block"><?php echo $msgPersona; ?></span>																
																<div class="help-block with-errors"></div>
																<script>
																	$("#formulario #TIP_DIVISAO").val('<?=$tip_divisao?>').trigger("chosen:updated");
																</script>	
														</div>
													</div>
													
												</div>

												<div class="push10"></div>

												<div class="row">
													
													<div class="col-md-12" id="divId_usu" style="<?=$displayUsu?>">
														<div class="form-group">
															<label for="inputName" class="control-label">Usuários</label>
															
															<select data-placeholder="Selecione um usuário" name="COD_USUARIO[]" id="COD_USUARIO" class="chosen-select-deselect">
																<option value="0"></option>					
															</select>	
																
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												
																	
											</div>

											<!-- aba roteiro -->
											<div id="roteiro" class="tab-pane fade">
											
												<div class="push30"></div>	
												
												<div class="row">													
													
													<div class="col-md-12 text-center">
														<div class="form-group">
															<label for="inputName" class="control-label">Script do Desafio</label><br/>
																<textarea class="form-control" rows="3" name="DES_DESAFIO" id="DES_DESAFIO" maxlength="5000"><?php echo $des_desafio ?></textarea>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>	
																	
											</div>

											<!-- aba unidades -->
											<div id="unidades" class="tab-pane fade">
											
												<div class="push30"></div>	
												
												<div class="row">													
													
													<div class="col-lg-12">

													<!-- <h4>Escolha a unidade desejada</h4> -->

													<div class="no-more-tables">
																								
														<table class="table table-bordered table-striped table-hover tableSorter">

															<thead>
																<tr>
																	<th class="text-center">Nro. Usuarios Loja</th>
																	<th>Código</th>
																	<th>Nome Fantasia</th>
																	<th></th>
																	<th class="text-center">Nro. Usuarios Loja</th>
																	<th>Código</th>
																	<th>Nome Fantasia</th>
																</tr>
															</thead>

															<tbody>
															  
															<?php 
															
																$sql = "SELECT UN.COD_UNIVEND, UN.NOM_FANTASI, UN.LOG_ESTATUS, UN.COD_PROPRIEDADE,
																		(SELECT COUNT(1) FROM USUARIOS US WHERE US.COD_UNIVEND = UN.COD_UNIVEND AND US.LOG_ESTATUS = 'S') AS QTD_USUARIOS
																		FROM UNIDADEVENDA UN
																		WHERE UN.COD_EMPRESA = $cod_empresa
																		AND UN.LOG_ESTATUS = 'S'
																		AND (UN.COD_EXCLUSA IS NULL OR UN.COD_EXCLUSA = 0) ORDER BY TRIM(UN.NOM_FANTASI)";

			                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																// fnEscreve($sql);
																
																$count=0;
																$inputsHidden = "";
																while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
																{			

																	if ($qrListaUniVendas['LOG_ESTATUS'] == 'S'){		
																		$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
																	}else{ 
																		$mostraAtivo = ''; 
																	}

																	$sqlCont = "SELECT COD_UNICONT FROM CONTRATO_UNIDADE WHERE COD_UNIVEND = $qrListaUniVendas[COD_UNIVEND] AND COD_CONTRAT = $cod_contrat";
																	$qrCont = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlCont));

																	$qtd_loja = 0;

																	if (!empty($qrCont['COD_UNICONT']) && isset($qrCont['COD_UNICONT']) && $qrCont['COD_UNICONT'] != '' ){		
																		$univendAtiva = 'checked';	
																	}else{ 
																		$univendAtiva = ''; 
																	}

																	if($count % 2 == 0){ 
																        $abreTR = "<tr>";
																        $fechaTR = "<td></td>";  
																    }else{
																    	$abreTR = "";
																        $fechaTR = "</tr>";
																    }

																    if ($qrListaUniVendas['COD_PROPRIEDADE'] == 2){
																    	$franqueado = "FRANQUIA";
																    }else if($qrListaUniVendas['COD_PROPRIEDADE'] == 1){
																    	$franqueado = "PRÓPRIA";
																    }else{
																    	$franqueado = "INDEFINIDO";
																    }

																    if($qrListaUniVendas['LOG_ESTATUS'] == "N"){
																    	$corInativa = "text-danger";
																    }else{
																    	$corInativa = "";
																    }

																	// fnEscreve(($count % 2));								
																	
																	echo $abreTR."
																		  <td class='text-center'><b>".$qrListaUniVendas['QTD_USUARIOS']."</b></td>
																		  <td class='$corInativa'>".$qrListaUniVendas['COD_UNIVEND']."</td>
																		  <td class='$corInativa'>".$qrListaUniVendas['NOM_FANTASI']."</td>

																		".$fechaTR;

																		$inputsHidden .= "
																			<input type='hidden' id='ret_COD_UNIVEND_".$count."' value='".fnEncode($qrListaUniVendas['COD_UNIVEND'])."'>
																			<input type='hidden' id='ret_NOM_UNIVEND_".$count."' value='".$qrListaUniVendas['NOM_UNIVEND']."'>
																			<input type='hidden' id='ret_NOM_FANTASI_".$count."' value='".$qrListaUniVendas['NOM_FANTASI']."'>
																		";

																	$count++; 
																}

																echo $inputsHidden;								
																
															?>
																
															</tbody>
														</table>

													</div>
													
												</div>
													
												</div>	
																	
											</div>
											
											<!-- aba comunicação -->
											<!-- 
											<div id="comunicacao" class="tab-pane fade">
											
												<div class="push30"></div>	
												
												<div class="row">													
													
													<div class="col-md-2 text-center">
																                       
														<span class="fa fa-envelope fa-2x"></span>
													
														<div class="form-group">
															<div class="push10"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" class="switch" value="S" <?=$mostraEmail?> >
															<span></span>
														</div>
														<div class="push10"></div>
														<span class="f12">e-Mail</span>
														
													</div>	
													
													<div class="col-md-2 text-center">
																                       
														<span class="fa fa-comment-alt fa-2x"></span>
													
														<div class="form-group">
															<div class="push10"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_SMS" id="LOG_SMS" class="switch" value="S" <?=$mostraSms?> >
															<span></span>
														</div>
														<div class="push10"></div>
														<span class="f12">SMS</span>
														
													</div>
													
													<div class="col-md-2 text-center">
													<div class="disabledBlock"></div>
																                       
														<span class="fab fa-whatsapp-square fa-2x"></span>
													
														<div class="form-group">
															<div class="push10"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_WPP" id="LOG_WPP" class="switch" value="S" <?=$mostraWpp?> >
															<span></span>
														</div>
														<div class="push10"></div>
														<span class="f12">Whats App</span>
														
													</div>	

													<div class="col-md-2 text-center"> 
													<div class="disabledBlock"></div>
																                       
														<span class="fas fa-bell fa-2x"></span>
													
														<div class="form-group">
															<div class="push10"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_PUSH" id="LOG_PUSH" class="switch" value="S" <?=$mostraPush?> >
															<span></span>
														</div>
														<div class="push10"></div>
														<span class="f12">Push</span>
														
													</div>

													<div class="col-md-2 text-center"> 
																                       
														<span class="fas fa-bell fa-2x"></span>
													
														<div class="form-group">
															<div class="push10"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_NPS" id="LOG_NPS" class="switch" value="S" <?=$mostraNps?> >
															<span></span>
														</div>
														<div class="push10"></div>
														<span class="f12">Pesquisa NPS</span>
														
													</div>
													
												</div>	
																	
											</div
											-->
											
										</div>	

														
							
											
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
										
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <?php if ($cod_desafio <> 0) { ?>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <?php } else { ?>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <?php } ?>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<?php if ($cod_desafio <> 0) { ?>
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<?php } else { ?>
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N'">		
										<?php } ?>
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div>
					
	<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
	
	<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
	
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 //maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			$('.clockPicker').datetimepicker({
				 format: 'LT',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
			});


			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");			
			var sistemasUni = "<?=$cod_univend?>";				
			var sistemasUniArr = sistemasUni.split(',');				
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
			  $("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
			}
			$("#formulario #COD_UNIVEND").trigger("chosen:updated");

			carregaUsuarios();			
			

		});		
		
        $(document).ready( function() {
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			$('#COD_PERSONA').chosen({ max_selected_options: 1});

			$('#COD_PERSONA').change(function(){

				let cod_persona = $(this).val();

				if(cod_persona != ""){
					$.ajax({
						type: "POST",
						url: "ajxBuscaUnidadePersona.do?id=<?=fnEncode($cod_empresa)?>",
						data: {COD_PERSONA: cod_persona},
						beforeSend:function(){
							// $('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
						},
						success:function(data){

							let obj = jQuery.parseJSON(data),
								blockUni = 0;

							$("#formulario #COD_UNIVEND").val("").trigger("chosen:updated");

							$.each(obj, function(key,value) {

								console.log(key + ":" + value);

								if(key != "LOG_UNIPREF"){
									if(value.trim() != ""){
										$("#formulario #COD_UNIVEND option[value=" + value + "]").prop("selected", "true");
										blockUni = 1;
									}
								}else{
									if(value == "S"){
										$("#formulario #TIP_GERACAO").val("PREF").trigger("chosen:updated");
									}else{
										$("#formulario #TIP_GERACAO").val("CAD").trigger("chosen:updated");
									}
								}

							});

							// if(blockUni == 1){
							// 	$("#blockUnivend").show();
							// }else{
							// 	$("#blockUnivend").hide();
							// }

							$("#blockLista").show();
							$("#formulario #COD_UNIVEND").trigger("chosen:updated");

						},
						error:function(data){
							
						}
					});
				}

			});

			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});
			
			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
			    $('#DES_ICONE').val(e.icon);
			    //alert($('#DES_ICONE').val());
			});

			icone = "<?php echo $des_icone?>";

			cor = "<?php echo $des_cor?>";

			if(icone == ""){
				icone = "fal fa-chart-bar";
			}

			if(cor == ""){
				cor = "#2C3E50";
			}
 
			$("#btniconpicker").iconpicker('setIcon', icone);
			$("#DES_ICONE").val(icone);

			$("#DES_COR").minicolors('value', cor);
			
			$("#TIP_DIVISAO").change(function(){
				if($(this).val() == "USER"){
					$("#divId_usu").fadeIn();
				}else{
					$("#COD_USUARIO").val('').trigger("chosen:updated");
					$("#divId_usu").fadeOut();
				}
			});

			$("#iAll,#iNone").click(function(){
				carregaUsuarios();
			});

			$("#COD_UNIVEND").change(function(){
				carregaUsuarios();
			});

        });
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		function carregaUsuarios(){
			$.ajax({
				type: "POST",
				url: "ajxBuscaUsuarioChaveDesafio.php",
				data: $("#formulario").serialize(),
				beforeSend:function(){
					$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_usu").html(data);
					$("#COD_USUARIO").val("<?=$cod_usuario?>").trigger("chosen:updated");	
					retornaFormUsuarios();
				},
				error:function(data){
					$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					console.log(data);
				}
			});						
		}

		function retornaFormUsuarios(){
			//retorno combo multiplo - usuarios
			$("#formulario #COD_USUARIO").val('').trigger("chosen:updated");			
			var sistemasUni = "<?=$cod_usuario?>";				
			var sistemasUniArr = sistemasUni.split(',');				
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
			  $("#formulario #COD_USUARIO option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
			}
			$("#formulario #COD_USUARIO").trigger("chosen:updated");
		} 
		
	</script>	