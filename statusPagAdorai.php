<?php


$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_statuspag =  fnLimpaCampoZero($_REQUEST['COD_STATUSPAG']);
		$tip_reserva =  fnLimpaCampoZero($_REQUEST['TIP_ACAORESERVA']);
		$abv_statuspag= fnLimpaCampo($_REQUEST['ABV_STATUSPAG']);
		$des_statuspag = fnLimpaCampo($_REQUEST['DES_STATUSPAG']);
		$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampoHtml($_REQUEST['DES_COR']);

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {			

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
				$sql = "INSERT INTO ADORAI_STATUSPAG(
					COD_EMPRESA,
					COD_USUCADA,
					ABV_STATUSPAG,
					DES_STATUSPAG,
					TIP_ACAORESERVA,
					DES_ICONE,
					DES_COR
					)
				VALUES(
					$cod_empresa,
					$cod_usucada,
					'$abv_statuspag',
					'$des_statuspag',
					'$tip_reserva',
					'$des_icone',
					'$des_cor'
					)
				";
					 //fnEscreve2($sql);
				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);

				if (!$arrayProc){
					$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
				break;
				case 'ALT':
				$sql = "UPDATE ADORAI_STATUSPAG SET 
				COD_ALTERAC = $cod_usucada,
				ABV_STATUSPAG = '$abv_statuspag',
				DES_STATUSPAG = '$des_statuspag',
				TIP_ACAORESERVA = '$tip_reserva',
				DES_ICONE = '$des_icone',
				DES_COR = '$des_cor',
				DAT_ALTERAC = NOW()
				WHERE 
				COD_STATUSPAG = $cod_statuspag AND COD_EMPRESA = $cod_empresa
				";

						//fnEscreve($sql);
				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}
				break;
				case 'EXC':
				$sql = "UPDATE ADORAI_STATUSPAG SET 
				COD_EXCLUSA = $cod_usucada,
				DAT_EXCLUSA = NOW()
				WHERE 
				COD_STATUSPAG = $cod_statuspag AND COD_EMPRESA = $cod_empresa
				";
				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
				}
				break;					
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_empresa = 274;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
}


?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}
	tr{
		border-bottom: none!important;
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
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
</div>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1019";
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

				<?php 
				$abaAdorai = 2006;
				include "abasAdorai.php"; 

				$abaManutencaoAdorai = 2019;
					//echo $abaUsuario;

					//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Categoria</legend> 

							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_STATUSPAG" id="COD_STATUSPAG" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipos ação Reserva</label>
											<select data-placeholder="Selecione os hotéis" name="TIP_ACAORESERVA" id="TIP_ACAORESERVA" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="0">Disponivel</option>             
												<option value="1">Reservado</option>
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição do Pagamento</label>
										<input type="text" class="form-control input-sm" name="DES_STATUSPAG" id="DES_STATUSPAG" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_STATUSPAG" id="ABV_STATUSPAG" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Cor</label>
										<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="" required>															
									</div>														
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Ícone</label><br/>
										<button class="btn btn-sm btn-primary btnSearchIcon" id="btnIcon" style="min-height: 33px; margin-top: 1px;" data-icon="" required></button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
									</div> 
								</div>

							</div>

						</fieldset>


						<div class="push10"></div>

						<div class="form-group text-right col-lg-12">
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<div class="push5"></div>

					</form>

					<div class="push50"></div>



					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class='{ sorter: false } text-center'></th>
										<th>Código</th>
										<th>Abreviação</th>
										<th>Descrição</th>
										<th>Tipo de Reserva</th>
										<th>Icone</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "SELECT * FROM ADORAI_STATUSPAG WHERE COD_EXCLUSA IS NULL";
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										if ($qrBusca['TIP_ACAORESERVA'] == 0){
											$reserva = 'Disponivel';
										}else if ($qrBusca['TIP_ACAORESERVA'] == 1){
											$reserva = 'Reservado';
										}
										echo "
										<tr>
											<td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></td>
											<td>".$qrBusca['COD_STATUSPAG']."</td>
											<td>".$qrBusca['ABV_STATUSPAG']."</td>														
											<td>".$qrBusca['DES_STATUSPAG']."</td>														
											<td>".$reserva."</td>														
											<td><span style='color: ".$qrBusca['DES_COR']."; font-size: 30px;' class='". $qrBusca['DES_ICONE'] ."'></td>														
										</tr>
										<input type='hidden' id='ret_COD_STATUSPAG_" . $count . "' value='" . $qrBusca['COD_STATUSPAG'] . "'>
										<input type='hidden' id='ret_ABV_STATUSPAG_" . $count . "' value='" . $qrBusca['ABV_STATUSPAG'] . "'>
										<input type='hidden' id='ret_DES_STATUSPAG_" . $count . "' value='" . $qrBusca['DES_STATUSPAG'] . "'>
										<input type='hidden' id='ret_TIP_ACAORESERVA_" . $count . "' value='" .$reserva. "'>
										<input type='hidden' id='ret_DES_COR_" . $count . "' value='" . $qrBusca['DES_COR'] . "'>
										<input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrBusca['DES_ICONE'] . "'>
										";
									}
									?>

								</tbody>
							</table>
						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

	function retornaForm(index) {
		$("#formulario #COD_STATUSPAG").val($("#ret_COD_STATUSPAG_" + index).val());
		$("#formulario #DES_STATUSPAG").val($("#ret_DES_STATUSPAG_" + index).val());
		$("#formulario #ABV_STATUSPAG").val($("#ret_ABV_STATUSPAG_" + index).val());
		$("#formulario #TIP_ACAORESERVA").val($("#ret_TIP_ACAORESERVA_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_"+index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_"+index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_"+index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready( function() {

			//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',				
			theme: 'bootstrap'
		});

			//icon picker
		$('.btnSearchIcon').iconpicker({ 
			cols: 8,
			iconset: 'fontawesome',   
			rows: 6,
			searchText: 'Procurar  &iacute;cone'
		});	

		$('.btnSearchIcon').on('change', function(e) { 
				//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);		
		});	

	});

	
</script>