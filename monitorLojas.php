<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

	//echo "<h5>_".$opcao."</h5>";

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
		
		$cod_monitor = fnLimpaCampoZero($_REQUEST['COD_MONITOR']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$alerta_cadastro = fnLimpaCampo($_POST['ALERTA_CADASTRO']);
		$alerta_venda = fnLimpaCampo($_POST['ALERTA_VENDA']);
		$des_email = fnLimpaCampo($_POST['DES_EMAIL']);
		$tempo_refresh = 0;

		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];			
		
		// fnEscreve($opcao);
		if ($opcao != ''){
			
				//mensagem de retorno
			switch ($opcao)
			{
				case 'CAD':

				$sql = "INSERT INTO MONITOR_LOJAS (
				COD_EMPRESA, 
				ALERTA_CADASTRO, 
				ALERTA_VENDA, 
				TEMPO_REFRESH,
				DES_EMAIL
				) VALUES (
				'$cod_empresa', 
				'$alerta_cadastro', 
				'$alerta_venda', 
				'$tempo_refresh',
				'$des_email'
			)";
						// fnescreve($sql);

			mysqli_query($connAdm->connAdm(),$sql);

			$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
			break;
			case 'ALT':

			$sql = "UPDATE MONITOR_LOJAS SET
			ALERTA_CADASTRO = '$alerta_cadastro', 
			ALERTA_VENDA = '$alerta_venda', 
			TEMPO_REFRESH = '$tempo_refresh',
			DES_EMAIL = '$des_email'
			WHERE COD_EMPRESA = '$cod_empresa' AND COD_MONITOR = $cod_monitor";

						// fnEscreve($sql);

			mysqli_query($connAdm->connAdm(),$sql);

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

if($val_pesquisa != ""){
	$esconde = " ";
}else{
	$esconde = "display: none;";
}

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
		//fnEscreve('entrou else');
}

	//busca revendas do usuário
include "unidadesAutorizadas.php"; 

$sql = "SELECT COD_MONITOR, ALERTA_CADASTRO, ALERTA_VENDA, DES_EMAIL FROM MONITOR_LOJAS WHERE COD_EMPRESA = $cod_empresa";
	// fnEscreve($sql);
$qrMon = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));

if(isset($qrMon)){
		// fnEscreve($qrMon['COD_MONITOR']);
	$cod_monitor = $qrMon['COD_MONITOR'];
	$exibeAlerta_cadastro = $qrMon['ALERTA_CADASTRO'];
	$exibeAlerta_venda = $qrMon['ALERTA_VENDA'];
	$des_email = $qrMon['DES_EMAIL'];
}else{
		// fnEscreve("else");
	$cod_monitor = "";
	$exibeAlerta_cadastro = "";
	$exibeAlerta_venda = "";
	$des_email = "";
}

	// fnEscreve($cod_monitor);
	// fnEscreve($alerta_cadastro);
	// fnEscreve($alerta_venda);

	//fnMostraForm();

?>

<style>

	.top,.bottom{
		height: 105px;
	}

</style>

<?php if ($popUp != "true"){ ?>
	<div class="push30"></div> 
<?php } ?>

<div class="row">
    
    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
	
	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
				
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#unidades">Lista das Unidades</a></li>
					<li><a data-toggle="tab" href="#config">Configurações do Monitor</a></li>
				</ul>
			
					<div class="tab-content buscavel">
						<!-- aba lista unidades -->
						<div id="unidades" class="tab-pane fade in active">

							<div class="push30"></div>

							<fieldset>
								<legend>Filtros</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo de Lojas</label>
											<?php include "grupoLojasComboMulti.php"; ?>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Região</label>
											<?php include "grupoRegiaoMulti.php"; ?>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>
									
									
								</div>
								
							</fieldset>
                                                </div>
                                        </div>
                                </div>
                </div>
        

                                <div class="push20"></div>

                                <div class="portlet portlet-bordered">

                                    <div class="portlet-body">

                                        <div class="login-form">
							
							<div class="push30"></div> 

							<div class="row">
								
								<div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
									<div class="input-group activeItem">
										<div class="input-group-btn search-panel">
											<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
												<span id="search_concept">Sem filtro</span>&nbsp;
												<span class="far fa-angle-down"></span>										                    	
											</button>
											<ul class="dropdown-menu" role="menu">
												<li class="divisor"><a href="#" id="ALL">Sem filtro</a></li>
												<li class="divider"></li>
												<li><a href="#NOM_EMPRESA" id="OK">Conformidade</a></li>
												<li><a href="#NOM_FANTASI" id="NOK">Não Conformidade</a></li>									                      
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>
								</div>

							</div>

							<div class="push30"></div> 	
							
							<div class="row">												
								

								<?php
								
								$sql = "SELECT UV.*, TP.DES_PROPRIEDADE from unidadevenda UV
								LEFT JOIN TPPROPRIEDADE TP ON TP.COD_PROPRIEDADE = UV.COD_PROPRIEDADE
								where UV.COD_EMPRESA = $cod_empresa 
								AND UV.COD_EXCLUSA = 0 
								AND UV.LOG_ESTATUS = 'S'
								AND UV.COD_UNIVEND IN($lojasSelecionadas)
								$andFiltro 
								ORDER BY TRIM(NOM_FANTASI)";

								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													//fnEscreve($sql);
													//pegar o tempo de alter
								$CONF_MONITOR="SELECT * FROM MONITOR_LOJAS WHERE COD_EMPRESA = $cod_empresa"; 
								$rwconfmonitor=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $CONF_MONITOR));
								
								$count=0;
								while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
								{														  
									$count++;
														//carrega dados do monitor
									$log_monitor = "SELECT * FROM LOG_MONITOR WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $qrListaUniVendas[COD_UNIVEND]";
									$LOGRS=mysqli_query(connTemp($cod_empresa, ''),$log_monitor);
									while ($log_monitorrs=mysqli_fetch_assoc($LOGRS)){        
										
										if($log_monitorrs['tip_dados']=='1')
										{
											$danger=''; 
											if($log_monitorrs['dt_ultvenda']!='')
											{
												
												$dt_ultvenda= fnDataFull($log_monitorrs['dt_ultvenda']); 
												$alerta_venda = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".$rwconfmonitor['alerta_venda']." minutes"));
												
												if($alerta_venda <= $log_monitorrs['dt_ultvenda'])
												{
													$vendaOK=1; 
													$successvenda='success';                                                                                                                               
													$textdangervenda='text-success';
												} else{
													$vendaOK=0;                                                                                                                                 
													$textdangervenda='text-danger';
												}
											}else{                                                                                                                             
												$textdangervenda='text-danger';
												$vendaOK=0; 
											}
										}
										if ($log_monitorrs['tip_dados']=='2') 
										{
											$danger1='';
											if($log_monitorrs['dt_ultcadcliente']!='')
											{
												$dt_ultcadcliente=fnDataFull($log_monitorrs['dt_ultcadcliente']); 
												$alerta_cadastro = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".$rwconfmonitor['alerta_cadastro']." minutes"));

												if($alerta_cadastro <= $log_monitorrs['dt_ultcadcliente'])
												{
													$cadOK=1;
													$successcad='success';                                                                                                                                
													$textdangerCad='text-success';
												} else{
													$cadOK=0;                                                                                                                                
													$textdangerCad='text-danger';
												} 
												
											}else{                                                                                                                             
												$textdangerCad='text-danger';
												$cadOK=0;
											}
										}
	                                                         //success
	                                                         //danger
	                                                         //text-danger

									}
									if($vendaOK==1 && $cadOK==1 )
									{
										$danger='success';
										$sucesso = "OK";  
									}else{
										$danger='danger';
										$sucesso = "NOK";    
									}    
									
									?>	
									
									<div class="col-md-2 text-center item-bd <?=$sucesso?>">

										<div class="panel">
											<a href="#">
												<div class="top <?php echo  $danger; ?>"><i class="fal fa-warehouse-alt fa-3x iwhite" aria-hidden="true"></i>
													<h6 style="padding:0; height: 200px; font-size: 12px;"><?php echo $qrListaUniVendas['NOM_FANTASI'] ?> </h6>												
												</div>
												<div class="bottom" style="height: 90px;">
													
													<span class="referencia-busca f13 <?php echo $textdangervenda ;?>" style="padding: 10px 0 0 0;">Última Venda: <b><?php echo $dt_ultvenda;?></b> </span>
													<div class="push5"></div>
													<span class="referencia-busca f13 <?php echo $textdangerCad?>" style="padding: 0 0 0 0;">Último Cadastro: <b><?php echo $dt_ultcadcliente?></b> </span>
													
												</div>
											</a>
										</div>

										
									</div>													
									
									
									<?php 		
								}											

								?>

								
								
							</div>

							
						</div>
						
						<!-- aba totem -->
						<div id="config" class="tab-pane fade">
							
							<div class="push30"></div>
							
							<fieldset>
								<legend>Configurações do Monitor</legend> 
								
								<div class="row">
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Alerta de Tempo de Cadastro</label>
											<input type="text" class="form-control text-center input-sm int" name="ALERTA_CADASTRO" id="ALERTA_CADASTRO" value="<?php echo $exibeAlerta_cadastro;?>" maxlength="4" data-error="Campo obrigatório" required>
											<div class="help-block with-errors">(em minutos)</div>
										</div>
									</div>	
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Alerta de Tempo de Venda</label>
											<input type="text" class="form-control text-center input-sm int" name="ALERTA_VENDA" id="ALERTA_VENDA" value="<?php echo $exibeAlerta_venda;?>" maxlength="4" data-error="Campo obrigatório" required>
											<div class="help-block with-errors">(em minutos)</div>
										</div>
									</div>

									<div class="col-md-8">
										<div class="form-group">
											<label for="inputName" class="control-label">Emails</label>
											<input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" maxlength="1000" value="<?php echo $des_email;?>">
										</div>
										<div class="help-block with-errors">Separar múltiplos emails por ";"</div>
									</div>
									
								</div>
								
								<div class="push10"></div>
								
							</fieldset>						
							
							<div class="push10"></div>
							<hr>	
							<div class="form-group text-right col-lg-12">
								
								<!--<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>-->
								<?php
								if($cod_monitor == ""){
									?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
									<?php
								}else{
									?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php
								}
								?>
								
								<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
								
							</div>

							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
							<input type="hidden" name="LOJAS" id="LOJAS" value="<?=$lojasSelecionadas?>">											
							<input type="hidden" name="COD_MONITOR" id="COD_MONITOR" value="<?=$cod_monitor?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
							
							<div class="push5"></div> 
							
						</div>

						
						<div class="push30"></div>
						
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
				
			</div>
		</div>
		<!-- fim Portlet -->
	</div>
    </form>
</div>					

<div class="push30"></div> 

<script type="text/javascript">
	
	$(document).ready(function() {
		setInterval(function() {
			cache_clear()
		}, 3000);
	});

	$("#OK").click(function(){
		$(".OK").fadeOut("fast");
		$(".NOK").fadeOut("fast");
		$(".OK").fadeIn("fast");
	});

	$("#NOK").click(function(){
		$(".OK").fadeOut("fast");
		$(".NOK").fadeOut("fast");
		$(".NOK").fadeIn("fast");
	});

	$("#ALL").click(function(){
		$(".OK").fadeOut("fast");
		$(".NOK").fadeOut("fast");
		$(".NOK").fadeIn("fast");
		$(".OK").fadeIn("fast");
	});

	$('#DES_EMAIL').on('keypress', function (e) {
		if (/^[a-zA-Z0-9\.\b]+$/.test(String.fromCharCode(e.keyCode))) {
			return;
		} else {
			e.preventDefault();
		}
	}); 
	
		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e){
			var value = $('#INPUT').val().toLowerCase().trim();
			if(value){
				$('#CLEARDIV').show();
			}else{
				$('#CLEARDIV').hide();
			}
			$('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
			});

			$('#CLEAR').click(function(){
				$('#INPUT').val('');
				$('#INPUT').focus();
				$('#CLEARDIV').hide();
				if("<?=$filtro?>" != ""){
					location.reload();
				}else{
					var value = $('#INPUT').val().toLowerCase().trim();
					if(value){
						$('#CLEARDIV').show();
					}else{
						$('#CLEARDIV').hide();
					}
					$(".buscavel .item-bd").each(function (index) {
						if (!index) return;
						$(this).find(".referencia-busca").each(function () {
							var id = $(this).text().toLowerCase().trim();
							var sem_registro = (id.indexOf(value) == -1);
							$(this).closest('.item-bd').toggle(!sem_registro);
							return sem_registro;
						});
					});
				}
			});

		    // $('#SEARCH').click(function(){
		    // 	$('#formulario').submit();
		    // });
		    
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();

			if(filtro == "sem filtro"){
				var value = $(el).val().toLowerCase().trim();
				if(value){
					$('#CLEARDIV').show();
				}else{
					$('#CLEARDIV').hide();
				}
				$(".buscavel .item-bd").each(function (index) {
					if (!index) return;
					$(this).find(".referencia-busca").each(function () {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('.item-bd').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		}

		
	</script>	