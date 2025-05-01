<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();

	if(isset($_GET['pop'])){
	    $popUp = fnLimpaCampo($_GET['pop']);
	  }else{
	    $popUp = '';
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

			$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$pct_reserva = fnLimpaCampo($_REQUEST['PCT_RESERVA']);

			if (isset($_POST['COD_PERSONA'])){
				$Arr_COD_PERSONAS = $_POST['COD_PERSONA'];			 
				 
				   for ($i=0;$i<count($Arr_COD_PERSONAS);$i++) 
				   { 
					$cod_personas = $cod_personas.$Arr_COD_PERSONAS[$i].",";
				   } 
				   
				   $cod_personas = rtrim($cod_personas,",");
				   $cod_personas = ltrim($cod_personas,",");
					
			}else{$cod_personas = "0";}

			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
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
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_campanha = fnDecode($_GET['idc']);	
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	$sql = "SELECT DES_CAMPANHA, DAT_INI, HOR_INI, DAT_FIM, HOR_FIM FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

	$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	if(isset($qrCamp)){
		$des_campanha = $qrCamp['DES_CAMPANHA'];
		$inicio = $qrCamp['DAT_INI']." ".$qrCamp['HOR_INI'];
		$fim = $qrCamp['DAT_FIM']." ".$qrCamp['HOR_FIM'];
	}else{
		$des_campanha = "";
	}

	
	//fnMostraForm();

?>
<link href='https://bevacqua.github.io/dragula/dist/dragula.min.css' rel='stylesheet' type='text/css' />

<style>

body{
	overflow: hidden;
}
	
.container {
  max-width: 100%;
  margin: 0;
  padding: 0;
}

/*.left {
  float: left;
  position: relative;
  width: 50%;
  height: 100%;
}

.right {
  float: left;
  position: relative;
  width: 40%;
  margin-left: 5%;
  height: 100%;
}*/

#display {
  background: #2d2d2d;
  border: 10px solid #000000;
  border-radius: 5px;
  font-size: 2em;
  color: white;
  height: 100px;
  min-width:200px;
  text-align: center;
  padding: 1em;
  display:table-cell;
  vertical-align:middle;
}

#drag-elements {
  display: block;
  /*background-color: #FAFBFC;*/
  border-radius: 5px;
  min-height: 50px;
  margin: 0 auto;
  height: auto;
  /*padding: 1em 2em;*/
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -o-user-select: none;
  user-select: none;
}

#drag-elements > div {
  cursor: move; /* fallback if grab cursor is unsupported */
    cursor: grab;
    cursor: -moz-grab;
    cursor: -webkit-grab;
  padding: 0.7em 0;
  /*argin: 0 1em 1em 0;*/
  /*width: 100%;*/
  /*box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);*/
  border: 2px solid #F4F4F4;
  border-radius: 3px;
  background: #FFF;
  transition: all .5s ease;
}

#drag-elements > div:active {
  cursor: move; /* fallback if grab cursor is unsupported */
    cursor: grab;
    cursor: -moz-grab;
    cursor: -webkit-grab;
  -webkit-animation: flickerAnimation 0.3s 0s infinite ease-in-out;
  animation: flickerAnimation 0.8s 0s infinite ease-in-out;
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome, Opera and Firefox */
  opacity: .6;
  border: 2px solid #000;
}

#drag-elements > div:hover {
  border: 2px solid gray;
  background-color: #e5e5e5;
}

#drop-target {
  border: 2px dashed #ECECEC;
  border-radius: 5px;
  min-height: 270px;
  margin: 0 auto;
  height: auto;
  padding: 2em;
  display: block;
  text-align: center;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -o-user-select: none;
  user-select: none;
}

#drop-target > div {
  transition: all .5s;
  cursor: move; /* fallback if grab cursor is unsupported */
    cursor: grab;
    cursor: -moz-grab;
    cursor: -webkit-grab;
  padding: 1em;
  margin: 0 1em 0.5em 0;
  width: 100%;
  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
  border-radius: 5px;
  border: 1px solid;
  /*background: #F7F7F7;*/
  transition: all .5s ease;
}

#drop-target > div:active {
  cursor: move; /* fallback if grab cursor is unsupported */
  cursor: grab;
  cursor: -moz-grab;
  cursor: -webkit-grab;
  opacity: .6;
  border: 2px solid #000;
  -webkit-animation: flickerAnimation 1s 0s infinite ease-in-out;
  animation: flickerAnimation 1s 0s infinite ease-in-out;
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome, Opera and Firefox */
}

@keyframes flickerAnimation {
  0%   { opacity:1; }
  50%  { opacity:0; }
  100% { opacity:1; }
}
@-o-keyframes flickerAnimation{
  0%   { opacity:1; }
  50%  { opacity:0; }
  100% { opacity:1; }
}
@-moz-keyframes flickerAnimation{
  0%   { opacity:1; }
  50%  { opacity:0; }
  100% { opacity:1; }
}
@-webkit-keyframes flickerAnimation{
  0%   { opacity:1; }
  50%  { opacity:0; }
  100% { opacity:1; }
}

@-webkit-keyframes wiggle {
  0% {
    -webkit-transform: rotate(0deg);
  }
  25% {
    -webkit-transform: rotate(2deg);
  }
  75% {
    -webkit-transform: rotate(-2deg);
  }
  100% {
    -webkit-transform: rotate(0deg);
  }
}

@keyframes wiggle {
  0% {
    transform: rotate(-2deg);
  }
  25% {
    transform: rotate(2deg);
  }
  75% {
    transform: rotate(-2deg);
  }
  100% {
    transform: rotate(0deg);
  }
}

.gu-mirror {
   cursor: move; /* fallback if grab cursor is unsupported */
    cursor: grabbing;
    cursor: -moz-grabbing;
    cursor: -webkit-grabbing;
  padding: 0.3em 0;
  margin: 0 1em 1em 0;
  /*width: 100%;*/
  /*box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);*/
  border: 2px solid #F4F4F4;
  border-radius: 3px;
  background: #FFF;
  transition: opacity 0.4s ease-in-out;
}

.gu-hide {
  display: none!important
}

.gu-unselectable {
  -webkit-user-select: none!important;
  -moz-user-select: none!important;
  -ms-user-select: none!important;
  user-select: none!important
}

.gu-transit {
  opacity: .2;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)";
  filter: alpha(opacity=20)
}

.barra:before{
	position:absolute;
	content: "|";
	/*margin-top: 3px;*/
	left:2px;
	font-size: 15px;
	font-weight: 1000;
	transform: scale(2,1.3);
	/*border-radius: 1px;*/
	/*background: #000;*/
}

#trigger:hover{
	cursor: default;
}

#trigger:active{
	cursor: default!important;
	-webkit-animation: none!important;
  	animation: none!important;
  	opacity: 1!important;
  	border: 2px solid skyblue!important;
}

.not-movable{
	/*padding-right: 0!important;
	padding-left: 0!important;*/
	/*margin-right: 0!important;*/
}

.not-movable:hover .transparency{
	opacity: 1!important;
}

.no-padding-sides{
	padding-left: 0;
	padding-right: 0;
}

</style>
					
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
					              <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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
								
								<h4 style="margin: 0 0 5px 0;"><span class="bolder"></span></h4>

								<div class="push20"></div>		
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										 
											
											<div class="container">

												<div class="row">
													
													<div class="col-md-4 col-sm-4 col-xs-4">
														
														<h4 style="font-size: 17px;">Ações</h4>
														<p><small>Arraste para o Bloco da Campanha</small></p>

													</div>

													<div class="col-md-6 col-sm-6 col-xs-8">
														<div class="col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-12 col-xs-offset-6">
															<h4 style="font-size: 17px;">Composição do WhatsApp da campanha</h4>
														</div>
													</div>

												</div>

												<div class="row">
											  
													<div class="col-md-4 col-sm-4 col-xs-4">

													    <div class="col-md-12 col-sm-12 col-xs-12" id="drag-elements">

													    <?php

													    	$sql = "SELECT * FROM BLOCO_COMUNICACAO WHERE COD_TPCOM = 2 ORDER BY NUM_ORDENAC";
													    	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

													    	while($qrBloco = mysqli_fetch_assoc($arrayQuery)){

													    ?>

														    	<div class="row movable" id="<?=$qrBloco[COD_BLTEMPL]?>">
														    		<div class="col-md-2 col-sm-2 col-xs-2 text-right barra" style="color:<?=$qrBloco['DES_COR']?>">
														    			<span class="<?=$qrBloco[DES_ICONE]?>" style="padding-top:2px; font-size: 21px;"></span>
														    		</div>
														    		<div class="col-md-10 col-sm-10 col-xs-10"><?=$qrBloco['NOM_BLTEMPL']?></div>
														    	</div>

													    <?php  
															}
													    ?>

													    </div>

													</div>

												    <div class="col-md-7 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-8 col-xs-offset-1">

												    	<div class="col-md-12 col-sm-12 col-xs-12" id="drop-target">

												    		<?php

												    			$sqlGtlh = "SELECT * FROM GATILHO_WHATSAPP WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";

												    			$arrayQueryGtlh = mysqli_query(connTemp($cod_empresa,''),$sqlGtlh);

												    			$gatilhoConfig = mysqli_num_rows($arrayQueryGtlh);

																$qrBuscaGtlh = mysqli_fetch_assoc($arrayQueryGtlh);

																switch ($qrBuscaGtlh['TIP_GATILHO']) {
																	case 'cadastro':
																		$tip_gatilho = "Cadastro";
																	break;

																	case 'venda':
																		$tip_gatilho = "Venda";
																	break;

																	case 'vendaFast':
																		$tip_gatilho = "Venda rápida";
																	break;

																	case 'cadFast':
																		$tip_gatilho = "Cadastro rápido";
																	break;

																	case 'vendaOn':
																		$tip_gatilho = "Venda Online (Apresentação)";
																	break;

																	case 'resgate':
																		$tip_gatilho = "Resgate";
																	break;

																	case 'inativos':
																		$tip_gatilho = "Inativos";
																	break;

																	case 'credExp':
																		$tip_gatilho = "Créditos a expirar";
																	break;

																	case 'individual':
																		$tip_gatilho = "Individual";
																		$detalhes = " <span class='fal fa-play-circle text-success'></span>&nbsp;
																					".fnDataShort($qrBuscaGtlh['DAT_INI'])."&nbsp;
																					".$qrBuscaGtlh['HOR_INI']."<br/>
																					  <span class='fal fa-stop-circle text-danger'></span>&nbsp;
																					".fnDataShort($qrBuscaGtlh['DAT_FIM'])."&nbsp;
																					".$qrBuscaGtlh['HOR_FIM'];
																	break;

																	case 'tokenCad':
																		$tip_gatilho = "Token de Cadastro";
																		$detalhes = " <div class='flexrow'>
																					  	  <div class='col'>$qrBuscaGtlh[HOR_ESPECIF] Hora</div>
																					  </div>";
																	break;

																	case 'aniv':

																		$sqlFreq = "SELECT TIP_FREQUENCIA, DES_FREQUENCIA 
																					FROM FREQUENCIA_COMUNICACAO
																					WHERE TIP_FREQUENCIA = $qrBuscaGtlh[DES_PERIODO]";
																		$arrayFreq = mysqli_query($connAdm->connAdm(),$sqlFreq);
																		$qrFreq = mysqli_fetch_assoc($arrayFreq);


																		$sqlHor = "SELECT TIP_HORARIO, DES_HORARIO 
																					FROM HORARIO_COMUNICACAO
																					WHERE TIP_HORARIO = $qrBuscaGtlh[TIP_MOMENTO]";
																		$arrayHor = mysqli_query($connAdm->connAdm(),$sqlHor);
																		$qrHor = mysqli_fetch_assoc($arrayHor);


																		$sqlCtrl = "SELECT TIP_CONTROLE, DES_CONTROLE 
																					FROM CONTROLE_COMUNICACAO
																					WHERE TIP_CONTROLE = $qrBuscaGtlh[TIP_CONTROLE]";
																		$arrayCtrl = mysqli_query($connAdm->connAdm(),$sqlCtrl);
																		$qrCtrl = mysqli_fetch_assoc($arrayCtrl);

																		switch($qrBuscaGtlh['DIAS_ANTECED']){

																			case 1:
																				$antecedencia = "1 dia";
																			break;

																			case 7:
																				$antecedencia = "7 dias";
																			break;

																			case 15:
																				$antecedencia = "15 dias";
																			break;

																			case 21:
																				$antecedencia = "21 dias";
																			break;

																			case 30:
																				$antecedencia = "30 dias";
																			break;

																			default:
																				$antecedencia = "Nenhuma";
																			break;

																		}

																		$tip_gatilho = "Aniversário";
																		$detalhes = " <div class='flexrow'>
																					  	  <div class='col'>$qrFreq[DES_FREQUENCIA]</div>
																					  	  <div class='col'>$antecedencia</div>
																					  	  <div class='col'>$qrHor[DES_HORARIO]</div>
																					  	  <div class='col'>$qrCtrl[DES_CONTROLE]</div>
																					  </div>";
																	break;

																	case 'sorteio':
																		$tip_gatilho = "Sorteio";
																		
																	break;

																	case 'sorteioIndic':
																		$tip_gatilho = "Sorteio (Indicação)";
																	break;

																	case 'cadWhatsApp':
																		$tip_gatilho = "Boas Vindas (WhatsApp)";
																	break;

																	case 'resgWhatsApp':
																		$tip_gatilho = "Confirmação de resgate (WhatsApp)";
																	break;

																	case 'credWhatsApp':
																		$tip_gatilho = "Crédito ganho no momento da compra (WhatsApp)";
																	break;
																	
																	default:
																		$tip_gatilho = "Gatilho: {configurar}";
																	break;
																}

												    		?>

												    		<div class="row not-movable" id="trigger">
												    			<div class="col-md-1 col-xs-2 text-right barra">
														    		<span class="fas fa-cogs" style="padding-top:2px; font-size: 21px;"></span>
														    	</div>
														    	<div class="col-md-8 col-xs-7 text-left no-padding-sides"><small class="f17"><?=$tip_gatilho?><br/><?=$detalhes?></small></div>
														    	<div class="col-md-3 col-xs-3 no-padding-sides">
														    		<a href="javascript:void(0)" class="btn btn-info btn-xs transparency" data-url="action.php?mod=<?php echo fnEncode(1907)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_campanha)?>&pop=true" data-title="Gatilho" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'>
														    			<span class="fal fa-edit"></span>
														    			&nbsp;Editar
														    		</a>
														    		&nbsp;
														    	</div>
														    	<!-- <div class="col-md-1 col-sm-2 col-xs-2 text-left no-padding-sides"><a href="javascript:void(0)" class="btn btn-danger btn-xs transparency">&nbsp;<span class="fal fa-times"></span>&nbsp;</a></div> -->
												    		</div>

												    		<?php

												    			$sql = "SELECT * FROM TEMPLATE_AUTOMACAO_WHATSAPP TA
																		WHERE TA.COD_EMPRESA = $cod_empresa 
																		AND TA.COD_CAMPANHA = $cod_campanha
																		ORDER BY TA.NUM_ORDENAC";
																// fnEscreve($sql);
																$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																$countBlocos = 0;
																$countMsg = 0;

																while($qrTempl = mysqli_fetch_assoc($arrayQuery)){

																	// fnEscreve($qrTempl['COD_BLTEMPL']);

																	switch($qrTempl['COD_BLTEMPL']){

																		case 25:

																			$sqlTplt = "SELECT TS.NOM_TEMPLATE FROM TEMPLATE_WHATSAPP TS 
																				        LEFT JOIN MENSAGEM_WHATSAPP ME ON ME.COD_TEMPLATE_WHATSAPP = TS.COD_TEMPLATE
																					    WHERE COD_TEMPLATE_BLOCO = $qrTempl[COD_TEMPLATE]";	
																			//fnEscreve($sql);
																			$arrayQueryTplt = mysqli_query(connTemp($cod_empresa,''),$sqlTplt);

																			$qrBuscaTplt = mysqli_fetch_assoc($arrayQueryTplt);

																			$nom_template = $qrBuscaTplt['NOM_TEMPLATE'];

																			if($nom_template == ""){
																				$nom_template = "{configurar}";
																			}

																			$texto = "$nom_template";
																			$texto2 = "Campanha #$cod_campanha: $des_campanha";
																			$tipo = "msg";
																			$modConfig = 1908;
																			$countMsg++;
																		break;

																		case 26:
																			$texto = "{configurar}";
																			$texto2 = "Campanha #$cod_campanha: $des_campanha";
																			$tipo = "wait";
																			$modConfig = 1654;
																		break;

																		case 27:

																			$sqlTag = "SELECT DES_TAG FROM TAGS_AUTOMACAO WHERE COD_TEMPLATE = $qrTempl[COD_TEMPLATE]";	
																			//fnEscreve($sql);
																			$arrayQueryTag = mysqli_query(connTemp($cod_empresa,''),$sqlTag);
																			$des_tags = "";

																			while($qrBuscaTag = mysqli_fetch_assoc($arrayQueryTag)){
																				$des_tags .= $qrBuscaTag["DES_TAG"].',';
																			}

																			$des_tags = rtrim($des_tags,',');

																			if($des_tags == ""){
																				$des_tags = "{configurar}";
																			}

																			$texto = "$des_tags";
																			$texto2 = "Campanha #$cod_campanha: $des_campanha";
																			$tipo = "tag";
																			$modConfig = 1655;

																		break;

																	}

																	$sql2 = "SELECT DES_COR, DES_ICONE FROM BLOCO_COMUNICACAO WHERE COD_BLTEMPL = $qrTempl[COD_BLTEMPL]";
																	// fnEscreve($sql2);
																	$qrIco = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql2));

															?>

																	<div class="row movable" id="BLOCO_<?=$qrTempl[COD_TEMPLATE]?>" style="border-color: <?=$qrIco['DES_COR']?>">
																		<div class="col-md-1 col-xs-2 text-right barra" style="color:<?=$qrIco['DES_COR']?>">
																    		<span class="<?=$qrIco[DES_ICONE]?>" style="padding-top:2px; font-size: 21px;"></span>
																    	</div>
																    	<div class="col-md-8 col-xs-6 text-left no-padding-sides"><?=$texto?></div>
																    	<div class="col-md-2 col-xs-3 text-right no-padding-sides">
																    		<a href="javascript:void(0)" class="btn btn-info btn-xs transparency openModal" data-url="action.php?mod=<?php echo fnEncode($modConfig)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&idt=<?=fnEncode($qrTempl[COD_TEMPLATE])?>&pop=true" data-title="<?=$texto2?>" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm"); ajustaModal("<?=$tipo?>");} catch(err) {}'>
																    			<span class="fal fa-edit"></span>
																    			&nbsp;Editar
																    		</a>
																    		&nbsp;
																    	</div>
																    	<div class="col-md-1 col-xs-1 text-left no-padding-sides"><a href="javascript:void(0)" class="btn btn-danger btn-xs transparency" onclick='excBloco("<?=$qrTempl[COD_TEMPLATE]?>","<?=$tipo?>")'>&nbsp;<span class="fal fa-times"></span>&nbsp;</a></div>
																	</div>

												    		<?php
												    				$countBlocos ++;


												    			}


												    		?>
												    		
												    	</div>

												    </div>

												</div>

											</div>


											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
											<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										
										</form>
										
									</div>								
								
								</div>

								<div class="push100"></div> 
								<div class="push100"></div>
								<div class="col-md-12">
									<a href="javascript:void(0)" class="btn btn-primary" onclick="proximoPasso()">Próximo Passo&nbsp;&nbsp;<span class="fal fa-arrow-right"></span></a>
								</div>
								<div class="push100"></div>

							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>				

					<script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>
	
	<script type="text/javascript">

		parent.$("#conteudoAba").css("height", ($(".portlet").height()+50) + "px");

		function ajustaModal(dataId){
			// alert(dataId);
			if(dataId == "tag"){
				parent.jQuery('#popModal').find('.modal-content').css({
		              'width':'1000px',
		              'height':'480px',
		              'marginLeft':'auto',
		              'marginRight':'auto',
		              'marginTop':'auto',
		              'marginBottom':'auto',
		              'overflow': 'hidden'
		        });
				parent.jQuery('#popModal').find('.modal-dialog').css({
					  'maxWidth':'1080px'
		       	});
			}else{
				parent.jQuery('#popModal').find('.modal-content').css({
		              'width':'auto',
		              'height':'700px'
		        });
				parent.jQuery('#popModal').find('.modal-dialog').css({
		              'maxWidth':'1080px'
		       	});
			}
		}

		$(function(){

			function $(id) {
			  return document.getElementById(id);
			}

			dragula([$('drag-elements'), $('drop-target')], {
				revertOnSpill: true,
				// removeOnSpill: true,
				copy: function (el, source) {
				    return source === $('drag-elements');
				},
				accepts: function (el, target, handle, sibling) {
	        		
	        		if(sibling){
						id_parente = sibling.id;
					}else{
						id_parente = "";
					}
					
					if(id_parente != "trigger"){
						return target !== $('drag-elements');
					}
				},
				moves: function (el, source, target, handle, sibling){
					// alert(el.id);
	        		if (el.id == "trigger") {
	            		return false;
	            		console.log("false");
	        		}else{
	        			return true;
	        			console.log("true");
	        		}
			    }
			}).on('drop', function(el, source, target) {

				// index = ([].slice.call(el.parentNode.childNodes).findIndex((item) => el === item)-1); // - posição do elemento na caixa
				id_elemento = el.id;

				if(jQuery.isNumeric(id_elemento)){
					el.id = "MOVED_"+id_elemento;
				}

				if(source.id != target.id){

					jQuery.ajax({
						method: 'POST',
						url: 'ajxAutomacaoWhatsApp_v2.do',
						data: {COD_BLTEMPL: id_elemento, COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_CAMPANHA: "<?=fnEncode($cod_campanha)?>"},
						beforeSend:function(){
							$(el.id).innerHTML = '<div class="loading" style="width: 100%;"></div>';
						},
						success:function(data){
							Ids = "";	
							jQuery("#"+el.id).replaceWith(data);
						},
						error:function(){
							$(el.id).innerHTML = '<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Itens não encontrados...</p>';
						}
					});

					parent.$("#conteudoAba").css("height","+=70px");

				}else{

					var Ids = "";
					jQuery('#drop-target .movable').each(function( index ) {
						Ids += jQuery(this).attr('id').substring(6) + ",";
					});

					var arrayOrdem = Ids.substring(0,(Ids.length-1));

					execOrdenacao(arrayOrdem,9,"<?=$cod_empresa?>");

				}

			});

			//modal close
			// parent.jQuery('.modal').on('hidden.bs.modal', function () {
			// 	parent.mudaAba("action.php?mod=<?php echo fnEncode(1500)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true");
			// });

		});

		function proximoPasso(){

			var gatilhoConfig = "<?=$gatilhoConfig?>",
				msgConfig = "<?=$countMsg?>",
				msgBlock = "";

			if(gatilhoConfig == 1 && msgConfig > 0){

				parent.$('#LISTA').click();

			}else{

				if(gatilhoConfig == 0){
					msgBlock = "Nenhum gatilho foi configurado,";
				}else{
					msgBlock = "Nenhuma mensagem foi configurada,";
				}

				parent.$.alert({
	              title: "Aviso",
	              content: msgBlock+" e não será possível ativar a campanha. Deseja prosseguir?",
	              type: 'orange',
	              buttons: {
	                "PROSSEGUIR": {
	                  btnClass: 'btn-primary',
	                    action: function(){
	                   		parent.$('#LISTA').click();
	                    }
	                },
	                "CANCELAR": {
	                  btnClass: 'btn-default',
	                    action: function(){
	                     
	                    }
	                }
	              },
	              backgroundDismiss: true
	    		});
			}

		}

		function refreshBloco(cod_empresa,cod_template) {
			jQuery.ajax({
				type: "GET",
				url: "ajxAutomacaoWhatsApp_v2.php?opcao=refresh",
				data: { ajx1:p1,ajx2:p2,ajx3:p3},
				success:function(data){
					console.log(data); 
				},
				error:function(data){
					console.log(data); 
				}
			});		
		}

		function execOrdenacao(p1,p2,p3) {
			jQuery.ajax({
				type: "GET",
				url: "ajxOrdenacaoEmp.php",
				data: { ajx1:p1,ajx2:p2,ajx3:p3},
				success:function(data){
					console.log(data); 
				},
				error:function(data){
					console.log(data); 
				}
			});		
		}

		function excBloco(id,tipo){
			// alert(id);
			jQuery.ajax({
				type: "POST",
				url: "ajxAutomacaoWhatsApp_v2.php?opcao=exc&tp="+tipo,
				data: {COD_TEMPLATE:id, COD_EMPRESA:"<?=fnEncode($cod_empresa)?>"},
				beforeSend:function(){
					jQuery("#BLOCO_"+id).html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					console.log(data);
					jQuery("#BLOCO_"+id).remove();
					var Ids = "";
					jQuery('#drop-target .movable').each(function( index ) {
						Ids += jQuery(this).attr('id').substring(6) + ",";
					});

					var arrayOrdem = Ids.substring(0,(Ids.length-1));

					execOrdenacao(arrayOrdem,9,"<?=$cod_empresa?>");
				},
				error:function(data){
					console.log(data); 
				}
			});
		}
		
	</script>	