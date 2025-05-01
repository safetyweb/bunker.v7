<?php
	//echo fnDebug('true');

$hashLocal = mt_rand();	


if( $_SERVER['REQUEST_METHOD']=='POST' )
{
	$request = md5( implode( $_POST ) );

	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request ){
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}else{
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);

		if (isset($_POST['COD_PERSONA'])){
			$Arr_COD_PERSONA = $_POST['COD_PERSONA'];

			for ($i=0;$i<count($Arr_COD_PERSONA);$i++) 
			{ 
				$cod_persona = $cod_persona.$Arr_COD_PERSONA[$i].",";
			} 

			$cod_persona = substr($cod_persona,0,-1);

		}else{$cod_persona = "0";}
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$pct_reserva = 0;

			//fnEscreve($cod_empresa);

		$procedureLista = "SP_RELAT_WHATSAPP_CLIENTE";

		if ($opcao != ''){


			$sqlDel = "DELETE FROM WHATSAPP_LOTE 
						WHERE COD_EMPRESA = $cod_empresa
						AND COD_CAMPANHA = $cod_campanha
						AND LOG_ENVIO = 'P'";

			mysqli_query(connTemp($cod_empresa,''),$sqlDel);

			$sqlProcCad = "CALL $procedureLista($cod_empresa, $cod_campanha, '$pct_reserva', '$cod_persona', 'CAD')";
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sqlProcCad);

			$qrTot = mysqli_fetch_assoc($retorno);

			$sql2 = "INSERT INTO WHATSAPP_PARAMETROS(
							COD_EMPRESA,
							COD_CAMPANHA,
							COD_PERSONAS,
							PCT_RESERVA,
							TOT_PERSONAS,
							CLIENTES_UNICOS,
							CLIENTES_UNICOS_WHATSAPP,
							CLIENTES_UNICO_PERC,
							TOTAL_CLIENTE_WHATSAPP_NAO,
							CLIENTES_OPTOUT,
							CLIENTES_BLACKLIST,
							COD_USUCADA
							) VALUES(
							$cod_empresa,
							$cod_campanha,
							'$cod_persona',
							'$pct_reserva',
							'".fnLimpaCampoZero($qrTot['TOTAL_PERSONAS'])."',
							'".fnLimpaCampoZero($qrTot['CLIENTES_UNICOS'])."',
							'".fnLimpaCampoZero($qrTot['CLIENTES_UNICOS_WHATSAPP'])."',
							'".fnLimpaCampoZero($qrTot['CLIENTES_UNICO_PERC'])."',
							'".fnLimpaCampoZero($qrTot['TOTAL_CLIENTE_WHATSAPP_NAO'])."',
							'".fnLimpaCampoZero($qrTot['CLIENTES_OPTOUT'])."',
							'".fnLimpaCampoZero($qrTot['CLIENTES_BLACKLIST'])."',
							$cod_usucada
						)";

			mysqli_query(connTemp($cod_empresa,''),$sql2);

			$sqlControle = "UPDATE WHATSAPP_LISTA_CONTROLE
							SET COD_LISTA = (
								SELECT MAX(COD_LISTA) AS COD_LISTA 
								FROM WHATSAPP_PARAMETROS 
								WHERE COD_CAMPANHA = $cod_campanha 
								AND COD_USUCADA = $cod_usucada
								)
							WHERE COD_CAMPANHA = $cod_campanha
							AND COD_LISTA = 0";

			mysqli_query(connTemp($cod_empresa,''),$sqlControle);

			$sqlLista = "SELECT COD_CLIENTE, NUM_CELULAR FROM WHATSAPP_LISTA
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha";

			$arrayLista = mysqli_query(connTemp($cod_empresa,''),$sqlLista);

			$sqlLimpaCel = "";

			while ($qrLista = mysqli_fetch_assoc($arrayLista)){

				$numCelular = fnlimpacelular($qrLista['NUM_CELULAR']);

				$sqlLimpaCel .= "UPDATE WHATSAPP_LISTA SET 
									NUM_CELULAR = '".$numCelular."'
									WHERE COD_CLIENTE = ".$qrLista['COD_CLIENTE']."
									AND COD_CAMPANHA = ".$qrLista['COD_CAMPANHA']."
									AND COD_EMPRESA = $cod_empresa";

			}

			mysqli_multi_query(connTemp($cod_empresa,''),$sqlLimpaCel);

			unset($sqlLimpaCel);

			$sqlDelete = "DELETE FROM WHATSAPP_LISTA 
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_CAMPANHA = $cod_campanha 
							AND NUM_CELULAR = ''";

			mysqli_query(connTemp($cod_empresa,''),$sqlDelete);

		}			

				//mensagem de retorno
		switch ($opcao){
			case 'CAD':
				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
			break;
			case 'ALT':
				$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
			break;
			case 'EXC':
				$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
			break;
		}	

		$msgTipo = 'alert-success';

	}  	

}

	//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_campanha = fnDecode($_GET['idc']);
	$sql = "SELECT * FROM CAMPANHA WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";

	$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

	if($qrResult = mysqli_fetch_assoc($query)){
		$cod_campanha = $qrResult['COD_CAMPANHA'];
		$des_campanha = $qrResult['DES_CAMPANHA'];
	}else{
		$cod_campanha = 0;
		$des_campanha = "";
	}

	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";

	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
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

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend> 

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cód. Campanha</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Campanha</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha; ?>">
										</div>
									</div>


									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Personas Participantes da Campanha</label>

											<select data-placeholder="Selecione as personas desejadas" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php

												if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){

													$andUnidade = "";

												} else {

													$andUnidade = "AND PERSONA.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";

												}

												$sql = "SELECT IFNULL(PERSONAREGRA.COD_REGRA,0) AS TEM_REGRA, 
												PERSONA.* 
												FROM PERSONA 
												LEFT JOIN PERSONAREGRA ON PERSONAREGRA.COD_PERSONA = PERSONA.COD_PERSONA
												WHERE COD_EMPRESA = $cod_empresa 
												$andUnidade
												GROUP BY COD_PERSONA
												ORDER BY DES_PERSONA ";

												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

												while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)){	

													if ($qrListaPersonas['LOG_ATIVO'] == "N"){
														$desabilitado = "disabled";
														$desabilitadoOnTxt = " (Off)";
													}else{
														$desabilitado = "";
														$desabilitadoOnTxt = "";
													}

													if ($qrListaPersonas['TEM_REGRA'] == "0"){
														$desabilitadoRg = " disabled";
														$desabilitadoRgTxt = " (s/ regra)";
													}else{
														$desabilitadoRg = "";
														$desabilitadoRgTxt = "";
													}

													echo"
													<option value='".$qrListaPersonas['COD_PERSONA']."' ".$desabilitado.$desabilitadoRg.">".ucfirst($qrListaPersonas['DES_PERSONA']).$desabilitadoRgTxt.$desabilitadoOnTxt."</option> 
													";

												}


												?>								
											</select>
											<span class="help-block"><?php echo $msgPersona; ?></span>																
											<div class="help-block with-errors"></div>
											<script>
																	//retorno combo multiplo
												if ("<?php echo $tem_personas; ?>" == "sim" ){
													var sistemasCli = "<?php echo $cod_persona; ?>";
													var sistemasCliArr = sistemasCli.split(',');
																		//opções multiplas
													for (var i = 0; i < sistemasCliArr.length; i++) {
														$("#formulario #COD_PERSONA option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");				  
													}
													$("#formulario #COD_PERSONA").trigger("chosen:updated");    
												} else {$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");}
											</script>	
										</div>

									</div>
								</div>
								<div class="push10"></div>															

							</fieldset>										

							<div class="push10"></div>
							<hr>
							<div class="form-group col-md-4">
								<a class="btn btn-info modalFull" href="action.do?mod=<?php echo fnEncode(1609)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" ><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Importar</a>

							</div>	
							<div class="form-group text-right col-md-8">
								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_persona != 0) { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } else { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
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

	<script type="text/javascript">


		$(document).ready( function() {

			$(".modalFull").click(function(){
				parent.$('#popModal').find('.modal-content').animate({
					'width':'100vw',
					'height':'99.5vh',
					'marginLeft':'auto',
					'marginRight':'auto'

				});
				parent.$('#popModal').find('.modal-dialog').animate({
					'margin':'0'
				});
			});

		});

	</script>	