<?php
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	$cod_hora = (@$_GET["id"] != ""?fnDecode(@$_GET["id"]):0);

	$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM usuarios where COD_USUARIO = '".$_SESSION["SYS_COD_USUARIO"]."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
	$cod_usuario = $qrBuscaUsuario["COD_USUARIO"];
	$nom_usuario = $qrBuscaUsuario["NOM_USUARIO"];
	
	$adm = ($cod_usuario == 28 || $cod_usuario == 14213 || $cod_usuario == 16928 || $cod_usuario == 0);
//	$adm = true;


	if ($cod_hora == ""){
		$sql = "SELECT MAX(COD_HORA) COD_HORA FROM horas_trabalhadas WHERE COD_USUARIO=0".$cod_usuario." AND DAT_ATIVIDADE=DATE(NOW()) AND HOR_FINAL IS NULL;";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBusca = mysqli_fetch_assoc($arrayQuery);
		if (isset($arrayQuery)){
			$cod_hora = $qrBusca["COD_HORA"];
		}
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
			//print_r($_POST);
			$_SESSION['last_request']  = $request;

			$dat_atividade = fnDataSql(@$_POST["DAT_ATIVIDADE"]);
			$cod_centrocusto = @$_POST["COD_CENTROCUSTO"];
			$hor_inicial = @$_POST["HOR_INICIAL"];
			$hor_final = @$_POST["HOR_FINAL"];
			$des_observacao = @$_POST["DES_OBSERVACAO"];
			$des_ip = (@$_SERVER['HTTP_CLIENT_IP'] != ""?$_SERVER['HTTP_CLIENT_IP']:(@$_SERVER['HTTP_X_FORWARDED_FOR'] <> ""?$_SERVER['HTTP_X_FORWARDED_FOR']:@$_SERVER['REMOTE_ADDR']));
			$des_navegador = $_SERVER['HTTP_USER_AGENT'];
			$opcao = $_REQUEST['opcao'];
			$hashForm = $_REQUEST['hashForm'];

			if ($cod_hora <= 0 && $dat_atividade == ""){
				$opcao = "";
				$msgRetorno = "Data da atividade deve ser preenchida!";	
				$msgTipo = 'alert-warning';
			}
			/*
			if ($hor_inicial == ""){
				$opcao = "";
				$msgRetorno = "Hora inicial deve ser preenchida!";	
				$msgTipo = 'alert-warning';
			}
			if ($hor_final == ""){
				$opcao = "";
				$msgRetorno = "Hora final deve ser preenchida!";	
				$msgTipo = 'alert-warning';
			}*/

			if ($opcao != ''){
				$sql = "CALL SP_ALTERA_HORAS_TRABALHADAS (
												'0".@$cod_hora."', 
												'".@$cod_usuario."', 
												'".@$cod_centrocusto."', 
												'".@$dat_atividade."', 
												'".@$hor_inicial."', 
												'".@$hor_final."', 
												'".@$des_observacao."', 
												'".@$des_ip."', 
												'".@$des_navegador."', 
												'".@$opcao."'    
												) ";

				//echo $sql;exit;
				//fnEscreve(connTemp($cod_empresa,"true"));
				fnEscreve($sql);
                                
				$result = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
				//$qrBuscaNovo = mysqli_fetch_assoc($result);
				
				//fnEscreve($qrBuscaNovo["COD_NOVO"]);
				
			 
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

				?>
				<script>
					window.parent.fechaFrame('<?=$msgTipo?>','<?=$msgRetorno?>');
				</script>						
				<?php				
			}  	

		}
	}


	$bloq = false;
	$exc = false;
	$dat_atividade = (@$_POST["DAT_ATIVIDADE"] <> ""?$_POST["DAT_ATIVIDADE"]:fnFormatDate(date("Y-m-d")));
	$hor_inicial = (@$_POST["HOR_INICIAL"] <> ""?$_POST["HOR_INICIAL"]:($cod_hora == 0?date("H:i"):""));
	$hor_final = (@$_POST["HOR_FINAL"] <> ""?$_POST["HOR_FINAL"]:($cod_hora <> 0?date("H:i"):""));
	$des_observacao = (@$_POST["DES_OBSERVACAO"] <> ""?$_POST["DES_OBSERVACAO"]:"");


	if ($cod_hora != 0){
		$sql = "SELECT COD_HORA,COD_USUARIO,DAT_ATIVIDADE,TIME_FORMAT(HOR_INICIAL,'%H:%i') HOR_INICIAL,TIME_FORMAT(HOR_FINAL,'%H:%i') HOR_FINAL,DES_OBSERVACAO
				FROM HORAS_TRABALHADAS WHERE COD_HORA = ".$cod_hora;
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBusca = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)){
			$dat_atividade = fnFormatDate($qrBusca['DAT_ATIVIDADE']);
			$hor_inicial = $qrBusca['HOR_INICIAL'];
			$hor_final = $qrBusca['HOR_FINAL'];
			$des_observacao = $qrBusca['DES_OBSERVACAO'];
			
			if ($hor_final == ""){
				$hor_final = date("H:i");
			}else{
				$bloq = true;
				$exc = true;
			}
		}

	}
	//defaul - perfil

	
	//fnMostraForm();


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

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						
						<fieldset>
							<legend>Dados Gerais</legend> 
									
								<div class="row">	

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Usu&aacute;rio</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>">
											<input type="hidden" class="form-control input-sm" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
										</div>														
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Centro de Custo</label>

											<select data-placeholder="Selecione uma ou mais unidades" name="COD_CENTROCUSTO" id="COD_CENTROCUSTO" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php													

												$sql = "SELECT * 
														FROM centro_custo";

												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												//fnTesteSql($connAdm->connAdm(),$sql);
												while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {

													print_r($qrListaCamp);

													$selected = '';

													echo "
													<option value='" . $qrListaCamp['ID'] . "'" . $selected . ">" . ucfirst($qrListaCamp['DESCRICAO'])."</option>  
													";
												}

												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data da Atividade</label>

											<div class="input-group date datePicker" id="DAT_ATIVIDADE_GRP">
											  <input type='text' class="<?=(!$adm && ($bloq == true || $cod_hora > 0)?"leitura":"")?> form-control input-sm data" name="DAT_ATIVIDADE" id="DAT_ATIVIDADE" value="<?=$dat_atividade?>"/>
											  <span style='<?=(!$adm && ($bloq == true || $cod_hora > 0)?"display:none;":"")?>' class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											  </span>
											</div>
											<div class="help-block with-errors"></div>
										</div>														
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Hora Inicial</label>
											<input type='text' class="<?=(!$adm && ($bloq == true || $cod_hora > 0)?"leitura":"")?> form-control input-sm hora" name="HOR_INICIAL" id="HOR_INICIAL" value="<?php echo $hor_inicial; ?>"/>
										</div>														
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?=($cod_hora > 0?"required":"")?>">Hora Final</label>
											<input type='text' class="<?=(!$adm && ($bloq == true)?"leitura":"")?> form-control input-sm hora" name="HOR_FINAL" id="HOR_FINAL" value="<?php echo $hor_final; ?>"/>
										</div>														
									</div> 
									
								</div>
								
								<div class="push10"></div>															
								
								<div class="row">													
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Atividade</label><br/>
											<input type='text' class="<?=(($adm) || ($bloq == true?"leitura":""))?> form-control input-sm" name="DES_OBSERVACAO" id="DES_OBSERVACAO" value="<?php echo $des_observacao; ?>"/>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
								</div>
								
						</fieldset>										
							
						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-md-12">
						<?php /*
							  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							  */ ?>
							  
							  <?php if ($cod_hora <> 0) { ?>
								<?php if ($exc != true){?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fas fa-arrow-down" aria-hidden="true"></i>&nbsp; Sa&iacute;da</button>
								<?php } elseif ($adm) { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="" aria-hidden="true"></i>&nbsp; Salvar</button>
									<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
								<?php } ?>	
							  <?php } else { ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fas fa-arrow-up" aria-hidden="true"></i>&nbsp; Entrada</button>
							  <?php } ?>
						</div>
						
						<input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_HORA" id="COD_HORA" value="<?php echo $cod_hora; ?>">
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


	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	
	<script type="text/javascript">
		
		
        $(document).ready( function() {

			$('.datePicker').datetimepicker({
			  format: 'DD/MM/YYYY',
			  maxDate: 'now',
			}).on('changeDate', function (e) {
			  $(this).datetimepicker('hide');
			});
			
			$("#opcao").val("--");
        });
		
	</script>	