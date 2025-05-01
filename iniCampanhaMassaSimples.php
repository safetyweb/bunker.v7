	<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	$mostraChecadoAT = "checked";				
	$mostraChecadoRT = "";

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

			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);		
			$des_campanha = fnLimpaCampo($_REQUEST['DES_CAMPANHA']);			

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
			// fnEscreve($cod_univend);

			if ($opcao != ''){	

				$log_sab = 'S';
				$log_dom = 'S';
				$log_seg = 'S';
				$log_ter = 'S';
				$log_qua = 'S';
				$log_qui = 'S';
				$log_sex = 'S';
				$dat_ini = $today = date("Y-m-d");
				
				$sql = "CALL SP_ALTERA_CAMPANHA (
				'0', 
				'".$cod_empresa."', 
				'9999', 
				'S', 
				'".$des_campanha."', 
				'MASS', 
				'fa-phone-alt', 
				'#5CFF66', 
				'N', 
				'Campanha comunicação', 
				'".$cod_usucada."', 
				'21',
				'S',
				'".$dat_ini."',
				NULL,
				'00:00:00',
				'',
				'N',
				'".$log_sab."',
				'".$log_dom."',
				'".$log_seg."',
				'".$log_ter."',
				'".$log_qua."',
				'".$log_qui."',
				'".$log_sex."',
				'CAD'    
			) ";

			$result = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());				
			$qrBuscaNovo = mysqli_fetch_assoc($result);

			$cod_campanha = $qrBuscaNovo["COD_NOVO"];

			//criar gatilho
			$sql = "INSERT INTO GATILHO_WHATSAPP(
				COD_EMPRESA,
				COD_CAMPANHA,
				TIP_GATILHO,
				TIP_CONTROLE,
				DES_PERIODO,
				TIP_MOMENTO,
				HOR_ESPECIF,
				DAT_INI,
				HOR_INI,
				LOG_DOMINGO,
				LOG_SEGUNDA,
				LOG_TERCA,
				LOG_QUARTA,
				LOG_QUINTA,
				LOG_SEXTA,
				LOG_SABADO,
				COD_USUARIO,
				LOG_STATUS
				) VALUES(
				'$cod_empresa',
				'$cod_campanha',
				'individual',
				'99',
				'99',
				'99',
				'0',
				'$dat_ini',
				'00:00:00',
				'N',
				'N',
				'N',
				'N',
				'N',
				'N',
				'N',
				'$cod_usucada',
				'S'
			);";


				$sql .= "INSERT INTO CONTROLE_SCHEDULE_WHATSAPP(
					COD_EMPRESA,
					COD_CAMPANHA,
					TIP_GATILHO,
					COD_USUCADA
					) VALUES(
					'$cod_empresa',
					'$cod_campanha',
					'individual',
					'$cod_usucada'
				);";

				mysqli_multi_query(ConnTemp($cod_empresa,''),$sql);

		//setar mensagem (template)
					$sql = "INSERT INTO TEMPLATE_AUTOMACAO_WHATSAPP(
						COD_EMPRESA,
						COD_CAMPANHA,
						COD_BLTEMPL
						) VALUES(
						$cod_empresa,
						$cod_campanha,
						25
					)";

					mysqli_query(connTemp($cod_empresa,''),$sql);				

				//mensagem de retorno
						switch ($opcao)
						{
							case 'CAD':

							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	

							$sql = "SELECT MAX(COD_CAMPANHA) AS COD_CAMPANHA 
							FROM CAMPANHA 
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_USUCADA = $cod_usucada";

							$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

							$cod_campanha = $qrCod['COD_CAMPANHA'];

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

			}


			?>

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
										Informe os dados para o preenchimento da sua <strong>Campanha</strong>. 
									</div>
								<?php } ?>

								<div class="alert alert-warning top30 bottom30" id="alert-inativa" role="alert" style="display: none;">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Ao inativar a campanha, <strong>todos os envios</strong> agendados serão <strong>cessados</strong> e <strong>não será possível</strong> retomá-los depois. 
								</div>

								<div class="login-form">

									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

										<fieldset>
											<legend>Dados Gerais</legend> 

											<div class="row">
												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label required">Código</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Empresa</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
														<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
													</div>														
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Título da Campanha</label>
														<input type="text" class="form-control input-sm" name="DES_CAMPANHA" id="DES_CAMPANHA" maxlength="50" value="<?php echo $des_campanha; ?>" required>
													</div>														
												</div>

											</div>

											<div class="push20"></div>

										</fieldset>

										<hr>
										<div class="push20"></div>

										<div class="form-group text-right col-md-12">
											<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
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

					<?php if($tip_campanha == 21){ ?>

						$("#LOG_ATIVO").change(function(){
							if(!this.checked) {
								$("#alert-inativa").fadeIn(1);
							}else{
								$("#alert-inativa").fadeOut(1);
							}
						});

					<?php } ?>

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

					});		

					$(document).ready( function() {

			//chosen
						$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
						$('#formulario').validator();

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
							icone = "fal fa-user-tag";
						}

						if(cor == ""){
							cor = "#2C3E50";
						}

						$("#btniconpicker").iconpicker('setIcon', icone);
						$("#DES_ICONE").val(icone);

						$("#DES_COR").minicolors('value', cor);

					});

					function retornaForm(index){
						$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
						$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
						$('#formulario').validator('validate');			
						$("#formulario #hHabilitado").val('S');						
					}

				</script>	