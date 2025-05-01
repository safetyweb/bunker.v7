<?php
	
	//require('sendinblue/Email.php');
	
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
			
			$cod_comunic = fnLimpaCampoZero($_REQUEST['COD_COMUNIC']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
			$cod_comunicacao = fnLimpaCampoZero($_REQUEST['COD_COMUNICACAO']);
			$cod_tipcomu = 1; //tipo email transacional -- comunicacao_tipo
			$des_texto_sms = fnLimpaCampo($_REQUEST['DES_TEXTO_SMS']);
			$cod_disparo = fnLimpaCampo($_REQUEST['COD_DISPARO']);
			$cod_modmail = fnLimpaCampo($_REQUEST['COD_MODMAIL']);	
			$cod_bancovar = fnLimpaCampo($_REQUEST['COD_BANCOVAR']);	 
			$cod_program = fnLimpaCampoZero($_REQUEST['COD_PROGRAM']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
			$cod_ctrlenv = fnLimpaCampoZero($_REQUEST['COD_CTRLENV']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			if ($opcao != ''){

				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
				
				$sql = "CALL SP_ALTERA_COMUNICACAO_MODELO (
				 '".$cod_comunic."', 
				 '".$cod_empresa."', 
				 '".$cod_campanha."', 
				 '".$cod_comunicacao."', 
				 '".$cod_tipcomu."', 
				 '".$des_texto_sms."', 
				 '".$cod_bancovar."', 
				 '".$cod_usucada."', 
				 '".$cod_disparo."', 
				 '".$cod_modmail."', 
				 '".$cod_ctrlenv."', 
				 '".$opcao."'    
				) ";
				
				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());	
				$row = mysqli_fetch_array($arrayQuery);

				if($opcao == 'CAD'){
					$cod_comunic = $row["ULTIMO_CODIGO"];
				}
				
				$sql = "CALL SP_ALTERA_COMUNICACAO_EMPRESAS (
				 ".$cod_empresa.", 
				 ".$cod_comunic.",
				 ".$cod_tipcomu.",
				 '".$opcao."'    
				) ";		
				
				//fnEscreve($sql);
				//fnTestesql($connAdm->connAdm(),$sql);
				mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
				
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
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($qrBuscaEmpresa)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			
			//liberação das abas
			$abaPersona	= "S";
			$abaVantagem = "S";
			$abaRegras = "S";
			$abaComunica = "S";
			$abaAtivacao = "N";
			$abaResultado = "N";

			//$abaPersonaComp = "completed ";
			$abaPersonaComp = "active ";
			$abaCampanhaComp = "active ";
			$abaRegrasComp = "completed ";
			$abaComunicaComp = "completed ";
			$abaResultadoComp = "";
			//revalidada na aba de regras	
			$abaAtivacaoComp = "";
			
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//busca dados da campanha
	$cod_campanha = fnDecode($_GET['idc']);	
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaCampanha)){
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
		
	}	
 		
	//busca dados do tipo da campanha
	$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '".$tip_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaTpCampanha)){
		$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
		$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
		$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
		$label_1 = $qrBuscaTpCampanha['LABEL_1'];
		$label_2 = $qrBuscaTpCampanha['LABEL_2'];
		$label_3 = $qrBuscaTpCampanha['LABEL_3'];
		$label_4 = $qrBuscaTpCampanha['LABEL_4'];
		$label_5 = $qrBuscaTpCampanha['LABEL_5'];
		
	}  

	
	//busca dados da campanha
	$cod_campanha = fnDecode($_GET['idc']);	
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaCampanha)){
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
		$cod_extcampanha = $qrBuscaCampanha['COD_EXT_CAMPANHA'];
		
	}

?>

<link rel="stylesheet" href="css/widgets.css" />

<style>

body{
    font-family: 'Roboto', sans-serif;
}

.scrollPersona {
	position: fixed;
	top: 15%;
	right: 0;
	-webkit-transform: translateX(-50%) translateY(-50%);
	-moz-transform: translateX(-50%) translateY(-50%);
	transform: translateX(-50%) translateY(-50%);
	letter-spacing: 1px;
	font-weight: 700;
	font-size: 2em;
	line-height: 2;
	width: 10em;
	text-align: center;
	height: 70px;
	opacity: 0.7;
	z-index: 5;
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

.notify-badge{
    position: absolute;
    right:36%;
    top:10px;
    background:#18bc9c;
    border-radius: 30px 30px 30px 30px;
    text-align: center;
    color:white;
    font-size:11px;
}

.notify-badge span{
	margin: 0 auto;
}

.pos{
	left: 145;
	top:-10;
	background: #ffbf00;
	font-size: 9px;
	padding-top: 7px;
}

.posHidden{
	display: none;
}

.bolder{
	font-weight: 1000!important;
}

.bold{
	font-weight: 500!important;
}

.chosen-container{
	width: 100%!important;
}

</style>

   
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
									</div>
									
									<?php 
									//$formBack = "1169";
									include "atalhosPortlet.php"; 
									?>	

								</div>

								<?php $abaCampanhas = 1254; include "abasCampanhasConfig.php"; ?>								
								
								<div class="push10"></div> 
								
								<div class="portlet-body">
								
									<?php
																	
									$sql1 = "SELECT COUNT(1) AS TEMACESSO FROM senhas_parceiro apar
												INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
												WHERE par.COD_TPCOM='1' 
												AND apar.COD_PARCOMU='15' 
												AND apar.COD_EMPRESA=$cod_empresa 
												AND apar.LOG_ATIVO='S'";
									$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
									//fnEscreve($sql1);
									$qrAcessoIntegracao = mysqli_fetch_assoc($arrayQuery1);									  
									$integracaoEmail = $qrAcessoIntegracao['TEMACESSO'];
									//fnEscreve($integracaoEmail);
									?>
									
									<?php if ($integracaoEmail == 0) { ?>
									<div class="alert alert-danger top30 bottom30" role="alert">
									<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Sua empresa não possui comunicação <b>ativa</b>. <br/>
									Entre em <b>contato</b> com o <b>suporte</b>.
									</div>
									<?php } ?>
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>

									<?php if ($log_bloquea == "S"){ ?>									
									<div class="alert alert-danger" role="alert">
									   Persona  <strong>bloqueada</strong> para edição.
									</div>
									<?php } ?>
									
									<div  class="col-sm-12"	style="padding-left: 0;">
									
									<h4>Campanha #<?php echo $cod_campanha; ?>: <?php echo $des_campanha; ?></h4>									
									<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
									
										
									</div>
									
									<div class="push20"></div> 
									
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
	
										<!-- <h1><?php echo $cmdPage; ?></h1> -->
	
										<div  class="col-sm-12"	style="padding-left: 0;">

											<div class="col-xs-2" style="padding-left: 0;"> <!-- required for floating -->
											  <!-- Nav tabs -->
											  <ul class="vTab nav nav-tabs tabs-left text-center">
												
												<li class="active vTab">				
													<a href="javascript:void(0)" id="TEMPLATES" data-url="action.php?mod=<?php echo fnEncode(1643)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" onclick="mudaAba($(this).attr('data-url'))" data-toggle="tab">
					
													<i class="fal fa-file-image fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Templates</h5>
													<div class="push5"></div>
													<small class="text-muted" style="font-size: 10px;">Passo 1</small>											
													</a>
												</li>
												
												<li class="vTab">				
													<a href="javascript:void(0)" id="AUTOMACAO" data-url="action.php?mod=<?php echo fnEncode(1635)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" onclick="mudaAba($(this).attr('data-url'))" data-toggle="tab">
													
													<i class="fal fa-cogs fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Automação <br/> <small>Agendamento</small></h5>
													<div class="push5"></div>
													<small class="text-muted" style="font-size: 10px;">Passo 2</small>
													</a>
												</li>
												
												<li class="vTab">				
													<a href="javascript:void(0)" id="LISTA" data-url="action.php?mod=<?php echo fnEncode(1641)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" onclick="mudaAba($(this).attr('data-url'))" onclick="mudaAba($(this).attr('data-url'))" data-toggle="tab">
													
													<i class="fal fa-clipboard-list fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Lista</h5>
													<div class="push5"></div>
													<small class="text-muted" style="font-size: 10px;">Passo 3</small>
													</a>
												</li>

												<li class="vTab">				
													<a href="javascript:void(0)" id="APROVACAO" data-url="action.php?mod=<?php echo fnEncode(1633)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" onclick="mudaAba($(this).attr('data-url'))" data-toggle="tab">
													
													<i class="fal fa-clipboard-check fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Aprovação</h5>
													<div class="push5"></div>
													<small class="text-muted" style="font-size: 10px;">Passo 4</small>
													</a>
												</li>
												
												<li class="vTab">				
													<a href="javascript:void(0)" id="ATIVACAO" data-url="action.php?mod=<?php echo fnEncode(1634)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" onclick="mudaAba($(this).attr('data-url'))" data-toggle="tab">
													
													<i class="fal fa-rocket fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Ativação</h5>
													<div class="push5"></div>
													<small class="text-muted" style="font-size: 10px;">Passo 5</small>
													</a>
												</li>
												
												<li class="vTab">				
													<a href="javascript:void(0)" id="RESULTADOS" data-url="action.php?mod=<?php echo fnEncode(1642)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" onclick="mudaAba($(this).attr('data-url'))" data-toggle="tab">
													
													<i class="fal fa-chart-line fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Resultados</h5>
													<div class="push5"></div>
													<small class="text-muted" style="font-size: 10px;">Passo 6</small>
													</a>
												</li>
												
											  </ul>
											</div>

											<div class="col-xs-10 no-scroll">
											  
												<iframe frameborder="0" id="conteudoAba" src="action.php?mod=<?php echo fnEncode(1643)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" style="width: 100%; min-height: 25vh;"></iframe>
											  
											</div>

											<div class="clearfix"></div>
																					
										</div>
										
										<input type="hidden" name="CONTROLE" id="CONTROLE" value="0">
										<input type="hidden" name="COD_PERSONA" id="COD_PERSONA" value="<?php echo $cod_persona; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">												
										
									</form>
																			
									<!-- modal -->									
									<div class="modal fade" id="popModalAux" tabindex='-1'>
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

									<div class="push50"></div> 
									
									<div class="clearfix"></div>									
									
									<div class="push50"></div> 
											
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
									<iframe frameborder="0" style="width: 100%; height: 90%"></iframe>
								</div>		
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					
					<div class="push20"></div> 
					
	
	
	<style>
	.button-checkbox .btn {
		font-size: 11px;
	}
	
	.btn-default {
		color: #ffffff;
		background-color: #c7d0d1;
		border-color: #c7d0d1;
		
	}	
	
	.btn-default:hover {
		color: #ffffff;
		background-color: #c7d0d1;
		border-color: #c7d0d1;
		
	}
	
	.button-checkbox .btn-info {
		color: #ffffff;
		background-color: #52a7e0 !important;
		border-color: #52a7e0 !important;
		box-shadow: none;
	}	
	
	.btn-default:focus, .btn-default.focus {
		color: #ffffff;
		background-color: #c7d0d1;
		border-color: #c7d0d1;	
		outline-color: #c7d0d1;	
	}
	

	
	</style>
	
	<script type="text/javascript">	
	
		$(document).ready(function(){
			// mudaAba("action.php?mod=<?php echo fnEncode(1407)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true");
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
		});	
	
	
		$(function () {
			$('.button-checkbox').each(function () {

				// Settings
				var $widget = $(this),
					$button = $widget.find('button'),
					$checkbox = $widget.find('input:checkbox'),
					color = $button.data('color'),
					settings = {
						on: {
							//icon: 'glyphicon glyphicon-check'
							icon: 'fa fa-check fa-1x'
						},
						off: {
							//icon: 'glyphicon glyphicon-unchecked'
							icon: 'fa fa-times fa-1x'
						}
					};

				// Event Handlers
				$button.on('click', function () {
					$checkbox.prop('checked', !$checkbox.is(':checked'));
					$checkbox.triggerHandler('change');
					updateDisplay();
				});
				$checkbox.on('change', function () {
					updateDisplay();
				});

				// Actions
				function updateDisplay() {
					var isChecked = $checkbox.is(':checked');

					// Set the button's state
					$button.data('state', (isChecked) ? "on" : "off");

					// Set the button's icon
					$button.find('.state-icon')
						.removeClass()
						.addClass('state-icon ' + settings[$button.data('state')].icon);

					// Update the button's color
					if (isChecked) {
						$button
							.removeClass('btn-default')
							.addClass('btn-' + color + ' active');
					}
					else {
						$button
							.removeClass('btn-' + color + ' active')
							.addClass('btn-default');
					}
				}

				// Initialization
				function init() {

					updateDisplay();

					// Inject the icon if applicable
					if ($button.find('.state-icon').length == 0) {
						$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
					}
				}
				init();
			});
			
			$('#formulario').submit(function(e) {
				var arrayVariaveis = "";
				$(".variaveis > span").each(function( index ) {	
					if($(this).children().hasClass('active')){
						if(arrayVariaveis === ''){
							arrayVariaveis += $(this).children().next().attr('id');							
						}else{
							arrayVariaveis += ',' + $(this).children().next().attr('id');
						}
					}
				});	
				
				$('#COD_BANCOVAR').val(arrayVariaveis);
			});
	

});	

	function abreModalPai(link,title,tam){

		if(tam == "lg"){

			try {
				parent.$('#popModal').find('.modal-content').css({
		              'width':'100vw',
		              'height':'99.5vh',
		              'marginLeft':'auto',
		              'marginRight':'auto'
		              
		       	});
		       	parent.$('#popModal').find('.modal-dialog').css({
		              'margin':'0'
		       	});
			}catch(err) {}

		}else{

			try {
				parent.$('#popModal').find('.modal-content').css({
		              'width':'auto',
		              'height':'850px',
		              'marginLeft':'auto',
		              'marginRight':'auto'
		       	});
		       	parent.$('#popModal').find('.modal-dialog').css({
		              'margin':'30px auto'
		       	});
			}catch(err) {}

		}

		try {

			parent.$('#popModal .modal-title').text(title);
			parent.$('#popModal iframe').attr("src",link+"&rnd="+Math.random());
			parent.$('#popModal').modal('show').appendTo('body');

		} catch(err) {}

	}

	function mudaFrame ( ifram , valor ) {

		ifram.css("height",valor);

	}

	function mudaAba(link){
		$("#conteudoAba").attr("src",link+"&rnd="+Math.random());
	}
	
		
	function retornaForm(index){
		$("#formulario #COD_COMUNIC").val($("#ret_COD_COMUNIC_"+index).val());
		$("#formulario #COD_COMUNICACAO").val($("#ret_COD_COMUNICACAO_"+index).val()).trigger("chosen:updated");
		$("#formulario #COD_DISPARO").val($("#ret_COD_DISPARO_"+index).val()).trigger("chosen:updated");
		$("#formulario #COD_MODMAIL").val($("#ret_COD_MODMAIL_"+index).val()).trigger("chosen:updated");
		$("#formulario #COD_CTRLENV").val($("#ret_COD_CTRLENV_"+index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');

		carregaBancoVar(index);
	}
	
	function carregaBancoVar(index){
		
		//limpar
		$(".variaveis > span").each(function( index ) {
			if($(this).children().hasClass('active')){
				$(this).children().removeClass('active');	
				$(this).children().toggleClass('btn-info btn-default');	
				$(this).children().children().toggleClass('fa-check fa-times');				
			}
		});		
		
		var bancoVar = $("#ret_COD_BANCOVAR_"+index).val().split(',');
		
		for(var i = 0; i < bancoVar.length; i++){
			$(".variaveis > span").each(function( index ) {
				if($(this).children().next().attr('id') == bancoVar[i]){
					$(this).children().addClass('active');
					$(this).children().toggleClass('btn-default btn-info');
					$(this).children().children().toggleClass('fa-times fa-check');
				}
			});
		}
	}

	function exportaRel(opcao,cod_disparo){
		$.confirm({
			title: 'Exportação',
			content: '' +
			'<form action="" class="formName">' +
			'<div class="form-group">' +
			'<label>Insira o nome do arquivo:</label>' +
			'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
			'</div>' +
			'</form>',
			buttons: {
				formSubmit: {
					text: 'Gerar',
					btnClass: 'btn-blue',
					action: function () {
						var nome = this.$content.find('.nome').val();
						if(!nome){
							$.alert('Por favor, insira um nome');
							return false;
						}
						
						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function(){
								var self = this;
								return $.ajax({
									url: "ajxResultadoEmails_V2.do?opcao="+opcao+"&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
									data: {COD_DISPARO: cod_disparo, COD_CAMPANHA: "<?=$cod_campanha?>"},
									method: 'POST'
								}).done(function (response) {
									self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
									var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function(response){
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									console.log(response);
								});
							},							
							buttons: {
								fechar: function () {
									//close
								}									
							}
						});								
					}
				},
				cancelar: function () {
					//close
				},
			}
		});	
	}

	function exportaLista(opcao,cod_disparo){
		$.confirm({
			title: 'Exportação',
			content: '' +
			'<form action="" class="formName">' +
			'<div class="form-group">' +
			'<label>Insira o nome do arquivo:</label>' +
			'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
			'</div>' +
			'</form>',
			buttons: {
				formSubmit: {
					text: 'Gerar',
					btnClass: 'btn-blue',
					action: function () {
						var nome = this.$content.find('.nome').val();
						if(!nome){
							$.alert('Por favor, insira um nome');
							return false;
						}
						
						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function(){
								var self = this;
								return $.ajax({
									url: "ajxListaEmails_V2.do?opcao="+opcao+"&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
									data: {COD_DISPARO: cod_disparo, COD_CAMPANHA: "<?=$cod_campanha?>"},
									method: 'POST'
								}).done(function (response) {
									self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
									var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function(response){
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									console.log(response);
								});
							},							
							buttons: {
								fechar: function () {
									//close
								}									
							}
						});								
					}
				},
				cancelar: function () {
					//close
				},
			}
		});	
	}
	
		
	</script>	